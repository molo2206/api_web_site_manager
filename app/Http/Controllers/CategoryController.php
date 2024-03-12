<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        if (Auth::user()->checkPermission('Categories', 'read')) {
            return response()->json([
                "data" => Category::where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function byType($type)
    {
        $categories = Category::where('deleted', 0)->where('status',1)->whereJsonContains('types', $type)->get();
        return response()->json([
            "type" => $type,
            "data" => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "locale" => "required",
            "types" => "required"
        ]);
        if (Auth::user()->checkPermission('Categories', 'create')) {
            $categories = Category::where('deleted', 0)->get();
            foreach ($categories as $row) {
                foreach ($row->translations as $trans) {
                    if ($trans->name == $request->name && $trans->locale == $request->locale) {
                        return response()->json([
                            "message" => trans('messages.elementExists')
                        ], 422);
                    }
                }
            }


            $data = [
                "types" => json_encode($request->types),
                $request->locale => [
                    'name' => $request->name
                ]
            ];
            $category = Category::create($data);
            return response()->json([
                "data" => $category->translations->where("locale", $request->locale)->first()->name,
                "message" => trans('messages.saved')
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
    public function edit($id)
    {
        if (Auth::user()->checkPermission('Categories', 'read')) {
            $category = Category::where('deleted', 0)->find($id);
            if ($category) {
                return response()->json([
                    "data" => $category
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


    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required",
            "locale" => "required",
            "types" => "required"
        ]);
        if (Auth::user()->checkPermission('Categories', 'update')) {
            $category = Category::where('deleted', 0)->find($id);
            if ($category) {
                $categories = Category::where('deleted', 0)->get();
                foreach ($categories as $row) {
                    foreach ($row->translations as $trans) {
                        if ($trans->name == $request->name && $trans->locale == $request->locale && $row->id !== $category->id) {
                            return response()->json([
                                "message" => trans('messages.elementExists')
                            ], 422);
                        }
                    }
                }
                $data = [
                    "types" => json_encode($request->types),
                    $request->locale => [
                        'name' => $request->name
                    ]
                ];
                $category->update($data);
                return response()->json([
                    "message" => trans('messages.updated'),
                    "data" => $category
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
    public function destroy(string $id)
    {
        if (Auth::user()->checkPermission('Categories', 'delete')) {
            $category = Category::where('deleted', 0)->find($id);
            if ($category) {
                $category->deleted = 1;
                $category->save();
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
        if (Auth::user()->checkPermission('Categories', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $category = Category::where('deleted', 0)->find($id);
            if ($category) {
                $category->update([
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
