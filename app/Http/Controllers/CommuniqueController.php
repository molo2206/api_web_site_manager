<?php

namespace App\Http\Controllers;

use App\Models\Communiques;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommuniqueController extends Controller
{
    public function index()
    {
        if (Auth::user()->checkPermission('Communiques', 'read')) {
            return response()->json([
                "data" => Communiques::with('author')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }

    public function getCommuniques()
    {
        return response()->json([
            "data" => Communiques::with('author')->where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required",
            "author" => "required",
            "description" => "required",
            "locale" => "required",
            "file" => "nullable",
        ]);
        if (Auth::user()->checkPermission('Rapports', 'create')) {
            $designation_fil = mt_rand(1, 9999999);
            $file = MethodsController::uploadDoc($request, $designation_fil, '/uploads/doc/communiques/', "file");
            $data = [
                "author" => $request->author,
                "file" => $file,
                "created" => $request->created,
                $request->locale => [
                    'title' => $request->title,
                    "description" => $request->description,
                ]
            ];
            $com = Communiques::create($data);
            return response()->json([
                "data" => $com,
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
        if (Auth::user()->checkPermission('Communiques', 'read')) {
            $com = Communiques::where('deleted', 0)->find($id);
            if ($com) {
                return response()->json([
                    "data" => $com
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
            "description" => "required",
            "locale" => "required",
            "file" => "nullable",
        ]);
        if (Auth::user()->checkPermission('Communiques', 'update')) {
            $rapport = Communiques::where('deleted', 0)->find($id);
            if ($rapport) {
                if ($request->file) {
                    $designation_fil = mt_rand(1, 99999999);
                    MethodsController::removeImageUrl($rapport->file);
                    $file = MethodsController::uploadDoc($request, $designation_fil, '/uploads/doc/communiques/', "file");
                } else {
                    $file = $rapport->file;
                }
                $data = [
                    "author" => $request->author,
                    "file" => $file,
                    "created" => $request->created,
                    $request->locale => [
                        'title' => $request->title,
                        "description" => $request->description,
                    ]
                ];
                $rapport->update($data);
                return response()->json([
                    "data" => $rapport,
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
        if (Auth::user()->checkPermission('Communiques', 'delete')) {
            $rapport = Communiques::where('deleted', 0)->find($id);
            if ($rapport) {
                $rapport->update([
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
        if (Auth::user()->checkPermission('Communiques', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $rapport = Communiques::where('deleted', 0)->find($id);
            if ($rapport) {
                $rapport->update([
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
