<?php

namespace App\Http\Controllers;

use App\Models\Offres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    public function index()
    {
        if (Auth::user()->checkPermission('Offres', 'read')) {
            return response()->json([
                "data" => Offres::with('author')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }

    public function getOffres()
    {
        return response()->json([
            "data" => Offres::with('author')->where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required",
            "place" => "required",
            "description" => "required",
            "startdate" => "required",
            "enddate" => "required",
            "file" => "required",
        ]);
        if (Auth::user()->checkPermission('Offres', 'create')) {
            $author = Auth::user();
            $designation_fil = mt_rand(1, 9999);
            $file = MethodsController::uploadDoc($request, $designation_fil, '/uploads/doc/offres/', "file");
            $data = [
                "author" => $author->id,
                "file" => $file,
                'title' => $request->title,
                "place" => $request->place,
                "description" => $request->description,
                "startdate" => $request->startdate,
                "enddate" => $request->enddate,
            ];
            $Offres = Offres::create($data);
            return response()->json([
                "data" => $Offres,
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
        if (Auth::user()->checkPermission('Offres', 'read')) {
            $event = Offres::where('deleted', 0)->find($id);
            if ($event) {
                return response()->json([
                    "data" => $event
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "title" => "required",
            "place" => "required",
            "description" => "required",
            "startdate" => "required",
            "enddate" => "required",
        ]);
        if (Auth::user()->checkPermission('Offres', 'update')) {
            $author = Auth::user();
            $offres = Offres::where('deleted', 0)->find($id);
            if ($offres) {
                if ($request->file) {
                    $designation_fil = mt_rand(1, 99999999);
                    MethodsController::removeImageUrl($offres->file);
                    $file = MethodsController::uploadDoc($request, $designation_fil, '/uploads/doc/offres/', "file");
                } else {
                    $file = $offres->file;
                }
                $data = [
                    "author" => $author->id,
                    "file" => $file ? $file : $offres->file,
                    "place" => $request->place,
                    'title' => $request->title,
                    "description" => $request->description,
                    "startdate" => $request->startdate,
                    "enddate" => $request->enddate,
                ];
                $offres->update($data);
                return response()->json([
                    "data" => $offres,
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
        if (Auth::user()->checkPermission('Offres', 'delete')) {
            $offres = Offres::where('deleted', 0)->find($id);
            if ($offres) {
                $offres->deleted = 1;
                $offres->update();
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
        if (Auth::user()->checkPermission('Offres', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $offres = Offres::where('deleted', 0)->find($id);
            if ($offres) {
                $offres->status =$request->status;
                $offres->update();
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
