<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\Bulletins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BulletinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->checkPermission('Blogs', 'read')) {
            return response()->json([
                "data" => Bulletins::with('author')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function getBulletins()
    {
        return response()->json([
            "data" => Bulletins::with('author')->where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required",
            "locale" => "required",
            "author" => "required",
            "description" => "required",
            "year" => "required",
            "month" => "required",
            "created" => "required",
            "file" => "nullable",
            "image" => "required|dimensions:min_width=850,min_height=550, max_width=950, max_height=650"
        ], ["image.dimensions" => "Invalid image sizes"]);
        if (Auth::user()->checkPermission('Bulletins', 'create')) {
            $designation_fil = mt_rand(1, 99999999);
            $image = MethodsController::uploadImageUrl($request->image, "/uploads/bulletins/");
            $file = MethodsController::uploadDoc($request, $designation_fil, '/uploads/doc/bulletins/', "file");
            $data = [
                "author" => $request->author,
                "image" => $image,
                "file" => $file,
                $request->locale => [
                    'title' => $request->title,
                    "description" => $request->description,
                    "year" => $request->year,
                    "month" => $request->month,
                    "created" => $request->created,
                ]
            ];
            $blogs = Bulletins::create($data);
            return response()->json([
                "data" => $blogs,
                "message" => trans('saved')
            ], 200);
        } else {
            return response()->json([
                "message" => trans('notAuthorized')
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(Event $event)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Auth::user()->checkPermission('Bulletins', 'read')) {
            $event = Bulletins::where('deleted', 0)->find($id);
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
            "locale" => "required",
            "author" => "required",
            "description" => "required",
            "year" => "required",
            "month" => "required",
            "created" => "required",
            "image" => "required|dimensions:min_width=850,min_height=550, max_width=950, max_height=650"
        ], ["image.dimensions" => "Invalid image sizes"]);
        if (Auth::user()->checkPermission('Bulletins', 'update')) {
            $blogs = Bulletins::where('deleted', 0)->find($id);
            if ($blogs) {
                if ($request->image) {
                    MethodsController::removeImageUrl($blogs->image);
                    $image = MethodsController::uploadImageUrl($request->image, "/uploads/bulletins/");
                } else {
                    $image = $blogs->image;
                }
                $data = [
                    "author" => $request->author,
                    "image" => $image,
                    $request->locale => [
                        'title' => $request->title,
                        "description" => $request->description,
                        "year" => $request->year,
                        "month" => $request->month,
                        "created" => $request->created,
                    ]
                ];
                $blogs->update($data);
                return response()->json([
                    "data" => $blogs,
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



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Auth::user()->checkPermission('Bulletins', 'delete')) {
            $blogs = Bulletins::where('deleted', 0)->find($id);
            if ($blogs) {
                $blogs->update([
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
        if (Auth::user()->checkPermission('Bulletins', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $blogs = Bulletins::where('deleted', 0)->find($id);
            if ($blogs) {
                $blogs->update([
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
