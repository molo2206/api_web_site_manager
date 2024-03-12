<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class roleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->checkPermission('Roles', 'read')) {
            return response()->json([
                "data" => Roles::with('permissions')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required"
        ]);
        if (Auth::user()->checkPermission('Roles', 'create')) {
            $role = Roles::where('name', $request->name)->first();
            if ($role) {
                $role->name = $request->name;
                $role->save();
                return response()->json([
                    "data" => $role,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                $role = new Roles();
                $role->name = $request->name;
                $role->save();
                return response()->json([
                    "data" => $role,
                    "message" => trans('messages.saved')
                ], 200);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Auth::user()->checkPermission('Roles', 'read')) {
            $role = Roles::with('permissions')->find($id);
            if ($role) {
                return response()->json([
                    "data" => $role
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.notFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required|unique:user_roles,name," . $id
        ]);
        if (Auth::user()->checkPermission('Roles', 'update')) {
            $role = Roles::with('permissions')->find($id);
            if ($role) {
                $role->name = $request->name;
                $role->save();
                return response()->json([
                    "message" => trans('messages.updated'),
                    "data" => $role
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.notFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->checkPermission('Roles', 'delete')) {
            $role = Roles::with('permissions')->find($id);
            if ($role) {
                $role->deleted = 1;
                $role->save();
                return response()->json([
                    "message" => trans('messages.deleted'),
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.notFound')
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
        if (Auth::user()->checkPermission('Roles', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $Roles = Roles::where('deleted', 0)->find($id);
            if ($Roles) {
                $Roles->update([
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

    public function assignPermissions(Request $request)
    {
        $request->validate([
            "role_id" => "required",
            "permissions" => "required|array|min:1"
        ]);

        if (Auth::user()->checkPermission('Users', 'update')) {
            $role = Roles::where('deleted', 0)->find($request->role_id);
            if ($role) {
                $role->permissions()->detach();
                foreach ($request->permissions as $item) {
                    $role->permissions()->attach([$item['ressource'] => ['create' => $item['create'], 'read' => $item['read'], 'update' => $item['update'], 'delete' => $item['delete']]]);
                }
                return response()->json([
                    "message" => trans('messages.saved'),
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.notFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
}
