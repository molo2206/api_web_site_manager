<?php

namespace App\Http\Controllers;

use App\Models\Testimonials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialsController extends Controller
{
    public function index()
    {
        if (Auth::user()->checkPermission('Testimonials', 'read')) {
            return response()->json([
                "data" => Testimonials::where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function getTestimonials()
    {
        return response()->json([
            "data" => Testimonials::where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }

    public function store(Request $request)
    {
        if (Auth::user()->checkPermission('Testimonials', 'create')) {
            $request->validate([
                "name" => "required",
                "fonction" => "required",
                "message" => "nullable",
                "image" => "required",
                "locale" => "required"
            ]);
            $image = MethodsController::uploadImageUrl($request->image, '/uploads/livres/');
            $body = [
                "name" => $request->name,
                "image" => $image,
                $request->locale => [
                    'fonction' => $request->fonction,
                    'message' => $request->message
                ]
            ];
            $data = Testimonials::create($body);
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
        if (Auth::user()->checkPermission('Testimonials', 'update')) {
            $request->validate([
                "name" => "required",
                "fonction" => "required",
                "message" => "nullable",
                "image" => "nullable",
                "locale" => "required"
            ]);
            $testi = Testimonials::where('deleted', 0)->find($id);
            if($testi){
                if($request->image){
                    $image = MethodsController::uploadImageUrl($request->image, '/uploads/livres/');
                } else {
                    $image = $testi->image;
                }
                
                $body = [
                    "name" => $request->name,
                    "image" => $image,
                    $request->locale => [
                        'fonction' => $request->fonction,
                        'message' => $request->message
                    ]
                ];
                $testi->update($body);
                return response()->json([
                    "data" => $testi,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.idNotFound')
                ], 422);
            }
            
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->checkPermission('Testimonials', 'delete')) {
            $team = Testimonials::where('deleted', 0)->find($id);
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
        if (Auth::user()->checkPermission('Testimonials', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $team = Testimonials::where('deleted', 0)->find($id);
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
