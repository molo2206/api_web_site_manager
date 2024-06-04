<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MethodsController;
use App\Models\Category;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->checkPermission('Events', 'read')) {
            return response()->json([
                "data" => Event::with('country', 'city')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
    public function getEventsById($event)
    {
        $evenement = Event::with(['category', 'country'])->find($event);
        if ($evenement) {
            return response()->json([
                "data" =>  $evenement
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.idNotFound')
            ], 404);
        }
    }

    public function getEvents()
    {
        return response()->json([
            "data" => Event::with('category')->where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }
    public function getEventsCategory($category)
    {
        $catego = Category::where('deleted', 0)->find($category);
        return response()->json([
            "data" => $catego->events()->with('category')->where('deleted', 0)->where('status', 1)->get()
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
        $request->validate([
            "title" => "required",
            "locale" => "required",
            "description" => "required",
            "category_id" => "required",
            "debut" => "nullable",
            "fin" => "nullable",
            "in" => "nullable",
            "out" => "nullable",
            "image" => "required|dimensions:min_width=850,min_height=550, max_width=950, max_height=650"
        ], ["image.dimensions" => "Invalid image sizes"]);
        if (Auth::user()->checkPermission('Events', 'create')) {
            $image = MethodsController::uploadImageUrl($request->image, "/uploads/events/");
            $data = [
                "category_id" => $request->category_id,
                "country_id" => $request->country_id ? $request->country_id : null,
                "city_id" => $request->city_id ? $request->city_id : null,
                "debut" => $request->debut,
                "fin" => $request->fin,
                "in" => $request->in,
                "out" => $request->out,
                "image" => $image,
                $request->locale => [
                    'title' => $request->title,
                    "description" => $request->description
                ]
            ];
            $event = Event::create($data);
            return response()->json([
                "data" => $event,
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
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Auth::user()->checkPermission('Events', 'read')) {
            $event = Event::where('deleted', 0)->find($id);
            if ($event) {
                return response()->json([
                    "data" => $event
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "title" => "required",
            "locale" => "required",
            "description" => "required",
            "category_id" => "required",
            "debut" => "nullable",
            "fin" => "nullable",
            "image" => "nullable|dimensions:min_width=850,min_height=550, max_width=950, max_height=650"
        ], ["image.dimensions" => "Invalid image sizes"]);
        if (Auth::user()->checkPermission('Events', 'update')) {
            $event = Event::where('deleted', 0)->find($id);
            if ($event) {

                if ($request->image) {
                    MethodsController::removeImageUrl($event->image);
                    $image = MethodsController::uploadImageUrl($request->image,"/uploads/events/");
                } else {
                    $image = $event->image;
                }
                $data = [
                    "category_id" => $request->category_id,
                    "country_id" => $request->country_id ? $request->country_id : $event->country_id,
                    "city_id" => $request->city_id ? $request->city_id : $event->city_id,
                    "debut" => $request->debut,
                    "fin" => $request->fin,
                    "in" => $request->in,
                    "out" => $request->out,
                    "image" => $image,
                    $request->locale => [
                        'title' => $request->title,
                        "description" => $request->description
                    ]
                ];
                $event->update($data);
                return response()->json([
                    "data" => $event,
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
            $event = Event::where('deleted', 0)->find($id);
            if ($event) {
                $event->update([
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
            $event = Event::where('deleted', 0)->find($id);
            if ($event) {
                $event->update([
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
