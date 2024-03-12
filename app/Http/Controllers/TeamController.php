<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MethodsController;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->checkPermission('Team', 'read')) {
            return response()->json([
                "data" => Team::where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
    public function getTeam()
    {
        return response()->json([
            "data" => Team::where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->checkPermission('Team', 'create')) {
            $request->validate([
                "full_name" => "required",
                "email" => "required",
                "facebook" => "required",
                "twitter" => "required",
                "instagram" => "required",
                "linkedin" => "required",
                "fonction" => "required",
                "image" => "required|dimensions:width=540,height=640"
            ], ["image.dimensions" => "Invalid image sizes"]);
            $image = MethodsController::uploadImageUrl($request->image, '/uploads/team/');

            $user = Team::create([
                "full_name" => $request->full_name,
                "email" => $request->email,
                "twitter" => $request->twitter,
                "facebook" => $request->facebook,
                "linkedin" => $request->linkedin,
                "instagram" => $request->instagram,
                "image" => $image,
                "fonction" => $request->fonction
            ]);
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

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->checkPermission('Team', 'create')) {
            $request->validate([
                "full_name" => "required",
                "email" => "required",
                "facebook" => "required",
                "twitter" => "required",
                "instagram" => "required",
                "linkedin" => "required",
                "fonction" => "required",
                "image" => "nullable|dimensions:width=540,height=640"
            ], ["image.dimensions" => "Invalid image sizes"]);

            $team = Team::where('deleted', 0)->find($id);
            if ($team) {
                if ($request->image) {
                    $image = MethodsController::uploadImageUrl($request->image, '/uploads/team/');
                } else {
                    $image = $team->image;
                }


                $team->update([
                    "full_name" => $request->full_name,
                    "email" => $request->email,
                    "twitter" => $request->twitter,
                    "facebook" => $request->facebook,
                    "linkedin" => $request->linkedin,
                    "instagram" => $request->instagram,
                    "image" => $image,
                    "fonction" => $request->fonction
                ]);
                return response()->json([
                    "data" => $team,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.idNotFound')
                ], 422);
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
    public function destroy($id)
    {
        if (Auth::user()->checkPermission('Team', 'delete')) {
            $team = Team::where('deleted', 0)->find($id);
            if ($team) {
                $team->update([
                    "deleted" => 1
                ]);
                return response()->json([
                    "message" => trans('messages.deleted')
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
        if (Auth::user()->checkPermission('Team', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $team = Team::where('deleted', 0)->find($id);
            if ($team) {
                $team->update([
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
