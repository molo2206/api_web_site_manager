<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class useController extends Controller
{
    private static function getUserImage()
    {
        return env('APP_URL') . '/uploads/users/avatar.jpg';
    }
    public function index()
    {
        if (Auth::user()->checkPermission('Users', 'read')) {
            return response()->json([
                "data" => User::with('role.permissions')->where('role_id','!=', NULL)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function getUserType()
    {
        if (Auth::user()->checkPermission('Users', 'read')) {
            return response()->json([
                "data" => User::with('role.permissions')->where('role_id','=', NULL)->where('type', 'Member')->orWhere('type', 'Partnar')->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function store(Request $request)
    {

        if (Auth::user()->checkPermission('Users', 'create')) {
            $request->validate([
                "full_name" => "required",
                "email" => "required",
                "gender" => "nullable",
                "role_id" => "required"
            ]);
            if(User::where('email', $request->email)->exists()){
                return response()->json([
                    "message" => trans('messages.userExists')
                ],422);
            }

            $user = User::create(array_merge(["password" => Hash::make(123456), "image" => $this->getUserImage()], $request->all()));
            return response()->json([
                "data" => $user,
                "message" => trans('messages.saved')
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {

        if (Auth::user()->checkPermission('Users', 'update')) {
            $request->validate([
                "full_name" => "nullable",
                "email" => "nullable",
                "gender" => "nullable",
                "role_id" => "nullable",
                "password" => "nullable"
            ]);
            $user = User::find($id);
            if($user){
                if(User::where('email', $request->email)->where('id', '!=', $id)->exists()){
                    return response()->json([
                        "message" => trans('messages.userExists')
                    ],422);
                }
                $user->full_name = $request->full_name ? $request->full_name : $user->full_name;
                $user->email = $request->email ? $request->email : $user->email;
                $user->gender = $request->gender ? $request->gender : $user->gender;
                $user->role_id = $request->role_id ? $request->role_id : $user->role_id;
                $user->password = $request->password ? Hash::make($request->password) : $user->password;
                $user->save();
                return response()->json([
                    "data" => $user,
                    "message" => trans('messages.updated')
                ],200);
            } else {
                return response()->json([
                    "message" => trans('messages.idNotFound')
                ],404);
            }
   
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function destroy(string $id)
    {
        if (Auth::user()->checkPermission('Users', 'delete')) {
            $User = User::find($id);
            if ($User) {
                $User->deleted = 1;
                $User->save();
                return response()->json([
                    "message" => trans('messages.deleted'),
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.idNotFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function status(Request $request, $id)
    {
        if (Auth::user()->checkPermission('Users', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $User = User::find($id);
            if ($User) {
                $User->update([
                    "status" => $request->status
                ]);
                return response()->json([
                    "message" => trans('messages.statusChange')
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.idNotFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
}
