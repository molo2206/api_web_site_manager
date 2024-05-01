<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->checkPermission('Blogs', 'read')) {
            return response()->json([
                "data" => Blogs::with('category','author')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
    public function getEventsById($event)
    {
        $blogs = Blogs::with(['category'])->find($event);
        if ($blogs) {
            return response()->json([
                "data" => $blogs
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.idNotFound')
            ], 404);
        }
    }

    public function getBlogs()
    {
        return response()->json([
            "data" => Blogs::with('category','author')->where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }
    public function getBlogsCategory($category)
    {
        $catego = Category::where('deleted', 0)->find($category);
        return response()->json([
            "data" => $catego->blogs()->with('category','author')->where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required",
            "publication_date" => "required",
            "locale" => "required",
            "author" => "required",
            "description" => "required",
            "documentation" => "required",
            "image" => "required|dimensions:min_width=850,min_height=550, max_width=950, max_height=650"
        ], ["image.dimensions" => "Invalid image sizes"]);
        if (Auth::user()->checkPermission('Blogs', 'create')) {
            $image = MethodsController::uploadImageUrl($request->image, "/uploads/blogs/");
            $data = [
                "category_id" => $request->category_id,
                "publication_date" => $request->publication_date,
                "author" => $request->author,
                "image" => $image,
                $request->locale => [
                    'title' => $request->title,
                    "description" => $request->description,
                    "documentation" => $request->documentation
                ]
            ];
            $blogs = Blogs::create($data);
            return response()->json([
                "data" => $blogs,
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
    // public function show(Event $event)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Auth::user()->checkPermission('Events', 'read')) {
            $event = Blogs::where('deleted', 0)->find($id);
            if ($event) {
                return response()->json([
                    "data" => $event
                ], 200);
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "title" => "required",
            "publication_date" => "required",
            "locale" => "required",
            "author" => "required",
            "description" => "required",
            "image" => "required|dimensions:min_width=850,min_height=550, max_width=950, max_height=650"
        ], ["image.dimensions" => "Invalid image sizes"]);
        if (Auth::user()->checkPermission('Blogs', 'update')) {
            $blogs = Blogs::where('deleted', 0)->find($id);
            if ($blogs) {
                if ($request->image) {
                    MethodsController::removeImageUrl($blogs->image);
                    $image = MethodsController::uploadImageUrl($request->image, "/uploads/blogs/");
                } else {
                    $image = $blogs->image;
                }
                $data = [
                    "category_id" => $request->category_id,
                    "publication_date" => $request->publication_date,
                    "author" => $request->author,
                    "image" => $image,
                    $request->locale => [
                        'title' => $request->title,
                        "description" => $request->description
                    ]
                ];
                $blogs->update($data);
                return response()->json([
                    "data" => $blogs,
                    "message" => trans('messages.updated')
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



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Auth::user()->checkPermission('Events', 'delete')) {
            $blogs = Blogs::where('deleted', 0)->find($id);
            if ($blogs) {
                $blogs->update([
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
        if (Auth::user()->checkPermission('Events', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $blogs = Blogs::where('deleted', 0)->find($id);
            if ($blogs) {
                $blogs->update([
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
