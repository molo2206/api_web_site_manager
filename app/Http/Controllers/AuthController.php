<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Mail\VerficationMail;
use App\Models\UserVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\MethodsController;
use Illuminate\Auth\Notifications\VerifyEmail;

class AuthController extends Controller
{
    private static function getUserImage()
    {
        return env('APP_URL') . '/uploads/users/avatar.jpg';
    }
    public function _login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::with('role.permissions')->where('email', $request->email)->first();
        if ($user) {
            if ($user->status == 1) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken("accessToken")->plainTextToken;
                    return response()->json([
                        "message" => trans("messages.loginSuccess"),
                        "data" => $user,
                        "status" => 200,
                        "token" => $token,
                    ], 200);
                } else {
                    return response()->json([
                        "message" => trans('messages.wrongPassword')
                    ], 422);
                }
            } else {
                return response()->json([
                    "message" => trans('messages.accountDisabled')
                ], 422);
            }
        } else {
            return response()->json([
                "message" => trans('messages.wrongEmail')
            ], 404);
        }
    }

    public function _showProfile()
    {
        return response()->json([
            "data" => User::with('role.permissions')->find(Auth::user()->id)
        ], 200);
    }
    public function _editProfile(Request $request)
    {
        $request->validate([
            "full_name" => "required",
            "email" => "email|unique:users,email," . Auth::user()->id,
            "phone" => "nullable||unique:users,phone," . Auth::user()->id,
            "gender" => "nullable"
        ]);
        Auth::user()->update([
            "full_name" => $request->full_name,
            "email"  => $request->email,
            "phone" => $request->phone,
            "gender" => $request->gender ? $request->gender : Auth::user()->gender
        ]);
        return response()->json([
            "message" => trans("messages.updated"),
            "data" => User::with('role.permissions')->find(Auth::user()->id)
        ], 200);
    }

    public function _editPassword(Request $request)
    {
        $request->validate([
            "old_password" => "required",
            "new_password" => "required"
        ]);
        $user = Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->update(['password' => Hash::make($request->new_password)]);
            return response()->json([
                "message" => trans("messages.updated"),
                "data" => $user,
                "status" => 200,
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.wrongOldPassword')
            ], 422);
        }
    }

    public function _editPhoto(Request $request)
    {
        $request->validate([
            "image" => "required",
        ]);
        $image = MethodsController::uploadImageUrl($request->image, "/uploads/users/");
        Auth::user()->update(["image" => $image]);
        return response()->json([
            "message" => trans("messages.updated"),
            "data" => User::with('role.permissions')->find(Auth::user()->id)
        ], 200);
    }

    public function _getLink(Request $request)
    {
        $request->validate([
            "email" => "required"
        ]);
        if ($request->action) {
            if ($request->action == 'register') {
                if (!User::where('email', $request->email)->exists()) {
                    $verify = UserVerification::where("email", $request->email)->first();
                    $settings = Settings::first();
                    if ($verify) {
                        try {
                            $token = $verify->createToken("accessToken")->plainTextToken;
                            Mail::to($request->email)->send(new VerficationMail(env('FRONT_URL') . '/verification?verify=' . $token, $settings));
                            $verify->update($request->all());

                            return response()->json([
                                "token" => $token,
                                "message" => trans('messages.validateEmailSent')
                            ]);
                        } catch (e) {
                            return response()->json([
                                "message" => trans('messages.mailNotSent')
                            ]);
                        }
                    } else {
                        try {
                            $verify = UserVerification::create($request->all());
                            $token = $verify->createToken("accessToken")->plainTextToken;
                            Mail::to($request->email)->send(new VerficationMail(env('FRONT_URL') . '/verification?verify=' . $token, $settings));
                            return response()->json([
                                "token" => $token,
                                "message" => trans('messages.emailSent')
                            ]);
                        } catch (e) {
                            return response()->json([
                                "message" => trans('messages.mailNotSent')
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        "message" => trans('messages.userExists')
                    ], 422);
                }
            }
        } else {
            $verify = UserVerification::where("email", $request->email)->first();
            if ($verify) {
                $verify->update($request->all());
                $token = $verify->createToken("accessToken")->plainTextToken;
                return response()->json([
                    "token" => $token,
                    "message" => trans('messages.emailSent')
                ]);
            } else {
                $verify = UserVerification::create($request->all());
                $token = $verify->createToken("accessToken")->plainTextToken;
                return response()->json([
                    "token" => $token,
                    "message" => trans('messages.emailSent')
                ]);
            }
        }
    }

    public function _checkLink(Request $request, $token)
    {
        $request->validate([
            "full_name" => "required",
            "email" => "required",
            "phone" => "required",
            "type" => "required",
            "gender" => "required",
            "password" => "required",
            "country" => "required",
            'town' => "required",
            'church' => 'nullable',
            'contribution' => 'nullable',
            'amount' => 'nullable',
            "ministere" => "nullable"
        ]);

        [$tokenable_id, $tkn] = explode('|', $token, 2);
        $token_data = DB::table('personal_access_tokens')->where('token', hash('sha256', $tkn))->first();
        if ($token_data) {
            $verify = UserVerification::find($token_data->tokenable_id);
            $user = User::create([
                "full_name" => $request->full_name,
                "email" => $request->email,
                "phone" => $request->phone,
                "type" => $request->type,
                "gender" => $request->gender,
                "country" => $request->country,
                'town' => $request->town,
                'chucrh_name' => $request->church,
                'contribution' => $request->contribution,
                'amount' => $request->amount,
                "ministere" => $request->ministere,
                "password" => Hash::make($request->password),
                "image" => $this->getUserImage()
            ]);
            $token = $user->createToken("accessToken")->plainTextToken;
            $tokens = DB::table('personal_access_tokens')->where('tokenable_id', $verify->id)->delete();


            $verify->delete();
            return response()->json([
                "message" => trans("messages.loginSuccess"),
                "data" => $user,
                "status" => 200,
                "token" => $token,
            ], 200);
        } else {
            return response()->json(
                ['message' => trans('messages.wrongUrl')],
                422
            );
        }
    }
}
