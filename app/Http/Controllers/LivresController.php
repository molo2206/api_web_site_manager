<?php

namespace App\Http\Controllers;

use App\Models\Livres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MethodsController;

class LivresController extends Controller
{
    public function index()
    {
        if (Auth::user()->checkPermission('Books', 'read')) {
            return response()->json([
                "data" => Livres::where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function getBooks()
    {
        return response()->json([
            "data" => Livres::where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }

    public function store(Request $request)
    {
        if (Auth::user()->checkPermission('Books', 'create')) {
            $request->validate([
                "title" => "required",
                "description" => "required",
                "file" => "nullable",
                "image" => "required",
                "price" => "nullable"
            ]);
            $image = MethodsController::uploadImageUrl($request->image, '/uploads/livres/');
            $file = MethodsController::uploadDoc($request, $request->title, '/uploads/doc/',"file");
            $data = Livres::create([
                "title" => $request->title,
                "description" => $request->description,
                "price" => $request->price,
                "file" => $request->file ? $file : null,
                "image" => $image,
            ]);
            return response()->json([
                "data" => $data,
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
        if (Auth::user()->checkPermission('Books', 'update')) {
            $request->validate([
                "title" => "required",
                "description" => "required",
                "file" => "nullable",
                "image" => "nullable",
                "price" => "nullable"
            ]);
            $book = Livres::where('deleted', 0)->find($id);
            if ($book) {
                if ($request->file) {
                    $file = MethodsController::uploadDoc($request, $request->title, '/uploads/doc/',$request->fil);
                } else {
                    $file = $book->file;
                }
                if ($request->image) {
                    $image = MethodsController::uploadImageUrl($request->image, '/uploads/livres/');
                } else {
                    $image = $book->image;
                }
                $book->update([
                    "title" => $request->title,
                    "description" => $request->description,
                    "price" => $request->price ? $request->price : $book->price,
                    "file" => $file,
                    "image" => $image,
                ]);
                return response()->json([
                    "data" => $book,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.idNotFound')
                ], 404);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->checkPermission('Books', 'delete')) {
            $team = Livres::where('deleted', 0)->find($id);
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
        if (Auth::user()->checkPermission('Books', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $team = Livres::where('deleted', 0)->find($id);
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
