<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\MethodsController;

class MediaController extends Controller
{
    public function index($type)
    {
        $data = Media::with('category', 'country')->where('type', $type)->get();
        return response()->json([
            "data" => $data
        ], 200);
    }
    public function store(Request $request)
    {

        if ($request->type == 'photo') {
            $request->validate([
                "cover" => "required",
                "category_id" => "required",
                "country_id" => "nullable",
                "city_id" => "nullable",
                "type" => "required"
            ]);
            foreach ($request->cover as $item) {
                $image = MethodsController::uploadImageUrl($item, '/uploads/media/');
                $data = Media::create([
                    "cover" => $image,
                    "category_id" => $request->category_id,
                    "country_id" => $request->country_id,
                    "city_id" => $request->city_id,
                    "type" => "photo"
                ]);
            }
            return response()->json([
                "message" => trans('messages.saved')
            ], 200);
        } else {
            $request->validate([
                "url" => "required",
                "cover" => "required",
                "category_id" => "required",
                "country_id" => "nullable",
                "city_id" => "nullable",
                "type" => "required"
            ]);

            $image = MethodsController::uploadImageUrl($request->cover[0], '/uploads/media/');
            $data = Media::create([
                "url" => $request->url,
                "cover" => $image,
                "category_id" => $request->category_id,
                "country_id" => $request->country_id,
                "city_id" => $request->city_id,
                "type" => "video"
            ]);

            return response()->json([
                "data" => $data,
                "message" => trans('messages.saved')
            ], 200);
        }
    }


    public function update(Request $request, $id)
    {

        $media = Media::where('deleted', 0)->find($id);
        if ($media) {
            if ($media->type == 'photo') {
                $request->validate([
                    "cover" => "nullable",
                    "category_id" => "required",
                    "country_id" => "nullable",
                    "city_id" => "nullable"
                ]);
                if ($request->cover) {
                    $image = MethodsController::uploadImageUrl($request->cover[0], '/uploads/media/');
                } else {
                    $image = $media->cover;
                }

                $media->update([
                    "cover" => $image,
                    "category_id" => $request->category_id,
                    "country_id" => $request->country_id ? $request->country_id : $media->country_id,
                    "city_id" => $request->city_id ? $request->city_id : $media->city_id,
                ]);
                return response()->json([
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                $request->validate([
                    "url" => "required",
                    "cover" => "nullable",
                    "category_id" => "required",
                    "country_id" => "nullable",
                    "city_id" => "nullable"
                ]);
                if ($request->cover) {
                    $image = MethodsController::uploadImageUrl($request->cover[0], '/uploads/media/');
                } else {
                    $image = $media->cover;
                }
                $media->update([
                    "url" => $request->url,
                    "cover" => $image,
                    "category_id" => $request->category_id,
                    "country_id" => $request->country_id ? $request->country_id : $media->country_id,
                    "city_id" => $request->city_id ? $request->city_id : $media->city_id,
                ]);

                return response()->json([
                    "data" => $media,
                    "message" => trans('messages.updated')
                ], 200);
            }
        } else {
            return response()->json([
                "message" => trans('messages.idNotFound')
            ], 404);
        }
    }
}
