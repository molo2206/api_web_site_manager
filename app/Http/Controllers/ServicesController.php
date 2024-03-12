<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MethodsController;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->checkPermission('Services', 'read')) {
            return response()->json([
                "data" => Services::where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function getServices()
    {
        return response()->json(
            [
                "data" => Services::where('deleted', 0)->where('status', 1)->get()
            ],
            200
        );
    }
    public function getServicesById($service)
    {
        $services = Services::where('deleted', 0)->where('status', 1)->find($service);

        if ($services) {
            $related = Services::where('deleted', 0)->where('status', 1)->where('id', '!=', $services->id)->inRandomOrder()->get();
            return response()->json([
                "data" =>  [
                    "data" => $services,
                    "related" => $related
                ]
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.idNotFound')
            ], 404);
        }
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
            "type" => "nullable",
            "image" => "required|dimensions:min_width=850,min_height=550, max_width=950, max_height=650"
        ], ["image.dimensions" => "Invalid image sizes"]);
        if (Auth::user()->checkPermission('Services', 'create')) {
            $image = MethodsController::uploadImageUrl($request->image, "/uploads/services/");
            $data = [
                "image" => $image,
                'type' => $request->type,
                $request->locale => [
                    'title' => $request->title,
                    "description" => $request->description
                ]
            ];
            $event = Services::create($data);
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
    public function show(Services $services)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Auth::user()->checkPermission('Services', 'read')) {
            $service = Services::where('deleted', 0)->find($id);
            if ($service) {
                return response()->json([
                    "data" => $service
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
            "type" => "nullable",
            "image" => "nullable|dimensions:min_width=850,min_height=550, max_width=950, max_height=650"
        ], ["image.dimensions" => "Invalid image sizes"]);
        if (Auth::user()->checkPermission('Services', 'update')) {
            $service = Services::where('deleted', 0)->find($id);
            if ($service) {

                if ($request->image) {
                    MethodsController::removeImageUrl($service->image);
                    $image = MethodsController::uploadImageUrl($request->image, "/uploads/services/");
                } else {
                    $image = $service->image;
                }
                $data = [
                    "image" => $image,
                    'type' => $request->type,
                    $request->locale => [
                        'title' => $request->title,
                        "description" => $request->description
                    ]
                ];
                $service->update($data);
                return response()->json([
                    "data" => $service,
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
    public function destroy(string $id)
    {
        if (Auth::user()->checkPermission('Services', 'delete')) {
            $Services = Services::where('deleted', 0)->find($id);
            if ($Services) {
                $Services->deleted = 1;
                $Services->save();
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
        if (Auth::user()->checkPermission('Services', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $Services = Services::where('deleted', 0)->find($id);
            if ($Services) {
                $Services->update([
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
