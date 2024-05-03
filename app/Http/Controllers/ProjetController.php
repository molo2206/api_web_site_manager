<?php

namespace App\Http\Controllers;

use App\Models\Projets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjetController extends Controller
{

    public function index()
    {
        if (Auth::user()->checkPermission('Projets', 'read')) {
            return response()->json([
                "data" => Projets::with('author')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }

    public function getprojets()
    {
        return response()->json([
            "data" => Projets::with('author')->where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required",
            "author" => "required",
            "locale" => "required",
            "image" => "nullable",
        ]);
        if (Auth::user()->checkPermission('Projets', 'create')) {
            $image = MethodsController::uploadImageUrl($request->image, "/uploads/projets/");
            $data = [
                "author" => $request->author,
                "image" => $image,
                $request->locale => [
                    'title' => $request->title,
                    "description" => $request->description,
                ]
            ];
            $projet = Projets::create($data);
            return response()->json([
                "data" => $projet,
                "message" => trans('saved')
            ], 200);
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->checkPermission('Projets', 'read')) {
            $projet = Projets::where('deleted', 0)->find($id);
            if ($projet) {
                return response()->json([
                    "data" => $projet
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('idNotFound')
                ], 404);
            }
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "title" => "required",
            "author" => "required",
            "locale" => "required",
            "image" => "nullable",
        ]);
        if (Auth::user()->checkPermission('Projets', 'update')) {
            $projet = Projets::where('deleted', 0)->find($id);
            if ($projet) {
                if ($request->image) {
                    MethodsController::removeImageUrl($projet->image);
                    $image = MethodsController::uploadImageUrl($request->image, "/uploads/projets/");
                } else {
                    $image = $projet->image;
                }
                $data = [
                    "author" => $request->author,
                    "image" => $image,
                    $request->locale => [
                        'title' => $request->title,
                        "description" => $request->description,
                    ]
                ];
                $projet->update($data);
                return response()->json([
                    "data" => $projet,
                    "message" => trans('updated')
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('idNotFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->checkPermission('Projets', 'delete')) {
            $projet = Projets::where('deleted', 0)->find($id);
            if ($projet) {
                $projet->update([
                    "deleted" => 1
                ]);
                return response()->json([
                    "message" => trans('deleted')
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('idNotFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }

    public function status(Request $request, $id)
    {
        if (Auth::user()->checkPermission('Projets', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $projet = Projets::where('deleted', 0)->find($id);
            if ($projet) {
                $projet->update([
                    "status" => $request->status
                ]);
                return response()->json([
                    "message" => trans('statusChange')
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('idNotFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }
}
