<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\MethodsController;
use App\Mail\ContactEmail;
use Exception;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "data" => Settings::first()
        ], 200);
    }

    public function storeAbout(Request $request)
    {
        $request->validate([
            "about_us" => "required",
            "mission" => "required",
            "vision" => "required",
            "history" => "nullable",
            "values" => "nullable",
            "locale" => "required"
        ]);
        if (Auth::user()->checkPermission('Settings', 'update')) {
            $settings = Settings::first();
            $data = [
                $request->locale => [
                    "about_us" => $request->about_us,
                    "mission" => $request->mission,
                    "vision" => $request->vision,
                    "history" => $request->history,
                    "values" => $request->values
                ]
            ];
            if ($settings) {
                $settings->update($data);
                return response()->json([
                    "data" => $settings,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                Settings::create($data);
                return response()->json([
                    "data" => Settings::first(),
                    "message" => trans('messages.saved')
                ], 200);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function storeSettings(Request $request)
    {
        $request->validate([
            "app_name" => "required",
            "social_links" => "required",
            "email" => "required",
            "phone" => "required",
            "stripe" => "nullable"
        ]);
        if (Auth::user()->checkPermission('Settings', 'update')) {
            $settings = Settings::first();
            if ($settings) {
                $settings->update([
                    "app_name" => $request->app_name,
                    "emails" => $request->email,
                    "phones" => $request->phone,
                    "stripe" => $request->stripe ? $request->stripe : null,
                    "social_links" => json_encode($request->social_links)
                ]);
                return response()->json([
                    "data" => $settings,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                Settings::create([
                    "app_name" => $request->app_name,
                    "emails" => $request->email,
                    "phones" => $request->phone,
                    "stripe" => $request->stripe ? $request->stripe : null,
                    "social_links" => json_encode($request->social_links)
                ]);
                return response()->json([
                    "data" => Settings::first(),
                    "message" => trans('messages.saved')
                ], 200);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function storeLogos(Request $request)
    {
        $request->validate([
            "logo1" => "nullable|image|max:2048",
            "logo2" => "nullable|image|max:2048"
        ]);
        if (Auth::user()->checkPermission('Settings', 'update')) {
            $settings = Settings::first();
            if ($settings) {
                if ($request->logo1) {
                    MethodsController::removeImageUrl($settings->logo1);
                    $logo1 = MethodsController::uploadImageUrl($request->logo1, '/uploads/logo/');
                } else {
                    $logo1 = $settings->logo1;
                }
                if ($request->logo2) {
                    MethodsController::removeImageUrl($settings->logo2);
                    $logo2 = MethodsController::uploadImageUrl($request->logo2, '/uploads/logo/');
                } else {
                    $logo2 = $settings->logo2;
                }
                $settings->update([
                    "logo1" => $logo1,
                    "logo2" => $logo2
                ]);
                return response()->json([
                    "data" => $settings,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                if ($request->logo1) {
                    $logo1 = MethodsController::uploadImageUrl($request->logo1, '/uploads/logo/');
                } else {
                    $logo1 = $settings->logo1;
                }
                if ($request->logo2) {
                    $logo2 = MethodsController::uploadImageUrl($request->logo2, '/uploads/logo/');
                } else {
                    $logo2 = $settings->logo2;
                }
                Settings::create([
                    "logo1" => $logo1,
                    "logo2" => $logo2
                ]);
                return response()->json([
                    "data" => Settings::first(),
                    "message" => trans('messages.saved')
                ], 200);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function storeImages(Request $request)
    {
        $request->validate([
            "image1" => "nullable|image|max:10240|dimensions:min_width=350,max_width=400,min_height=500",
            "image2" => "nullable|image|max:10240",
            "image3" => "nullable|image|max:10240",
            "image4" => "nullable|image|max:10240",
            "image5" => "nullable|image|max:10240",
        ]);
        if (Auth::user()->checkPermission('Settings', 'update')) {
            $settings = Settings::first();
            if ($settings) {
                if ($request->image1) {
                    MethodsController::removeImageUrl($settings->image1);
                    $image1 = MethodsController::uploadImageUrl($request->image1, '/uploads/settings/');
                } else {
                    $image1 = $settings->image1;
                }
                if ($request->image2) {
                    MethodsController::removeImageUrl($settings->image2);
                    $image2 = MethodsController::uploadImageUrl($request->image2, '/uploads/settings/');
                } else {
                    $image2 = $settings->image2;
                }
                if ($request->image3) {
                    MethodsController::removeImageUrl($settings->image3);
                    $image3 = MethodsController::uploadImageUrl($request->image3, '/uploads/settings/');
                } else {
                    $image3 = $settings->image3;
                }
                if ($request->image4) {
                    MethodsController::removeImageUrl($settings->image4);
                    $image4 = MethodsController::uploadImageUrl($request->image4, '/uploads/settings/');
                } else {
                    $image4 = $settings->image4;
                }
                if ($request->image5) {
                    MethodsController::removeImageUrl($settings->image5);
                    $image5 = MethodsController::uploadImageUrl($request->image5, '/uploads/settings/');
                } else {
                    $image5 = $settings->image5;
                }
                $settings->update([
                    "image1" => $image1,
                    "image2" => $image2,
                    "image3" => $image3,
                    "image4" => $image4,
                    "image5" => $image5,
                ]);
                return response()->json([
                    "data" => $settings,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                if ($request->image1) {
                    MethodsController::removeImageUrl($settings->image1);
                    $image1 = MethodsController::uploadImageUrl($request->image1, '/uploads/settings/');
                } else {
                    $image1 = $settings->image1;
                }
                if ($request->image2) {
                    MethodsController::removeImageUrl($settings->image2);
                    $image2 = MethodsController::uploadImageUrl($request->image2, '/uploads/settings/');
                } else {
                    $image2 = $settings->image2;
                }
                if ($request->image3) {
                    MethodsController::removeImageUrl($settings->image3);
                    $image3 = MethodsController::uploadImageUrl($request->image3, '/uploads/settings/');
                } else {
                    $image3 = $settings->image3;
                }
                if ($request->image4) {
                    MethodsController::removeImageUrl($settings->image4);
                    $image4 = MethodsController::uploadImageUrl($request->image4, '/uploads/settings/');
                } else {
                    $image4 = $settings->image4;
                }
                if ($request->image5) {
                    MethodsController::removeImageUrl($settings->image5);
                    $image5 = MethodsController::uploadImageUrl($request->image5, '/uploads/settings/');
                } else {
                    $image5 = $settings->image5;
                }
                Settings::create([
                    "image1" => $image1,
                    "image2" => $image2,
                    "image3" => $image3,
                    "image4" => $image4,
                    "image5" => $image5,
                ]);
                return response()->json([
                    "data" => Settings::first(),
                    "message" => trans('messages.saved')
                ], 200);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function contact(Request $request)
    {
        $request->validate([
            'first_name' => "required",
            "last_name" => "required",
            "email" => "required",
            "phone" => "required",
            "message" => "required"
        ]);
        $data = [
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "phone" => $request->phone,
            "message" => $request->message,
        ];
        $setting = Settings::first();
        try {
            Mail::to($setting->emails)->send(new ContactEmail($data));
            return response()->json([
                "message" => trans('messages.emailSent')
            ], 200);
        } catch (Exception $ex) {
            return response()->json([
                "message" => "error" . $ex->getMessage()
            ], 500);
        }
    }
}
