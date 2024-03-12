<?php

namespace App\Http\Controllers;

use App\Models\Langues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguesController extends Controller
{
    public function index()
    {
        if (Auth::user()->checkPermission('Langues', 'read')) {
            return response()->json([
                "data" => Langues::where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
    public function getLanguages()
    {
        return response()->json([
            "data" => Langues::where('deleted', 0)->get()
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "iso" => "required",
            "flag" => "required"
        ]);
        if (Auth::user()->checkPermission('Langues', 'create')) {
            $langue = Langues::where('name', $request->name)->first();
            if ($langue) {
                $langue->name = $request->name;
                $langue->iso = $request->iso;
                $langue->flag = $request->flag;
                $langue->deleted = 0;
                $langue->save();
                return response()->json([
                    "data" => $langue,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                $langue = new Langues();
                $langue->name = $request->name;
                $langue->iso = $request->iso;
                $langue->flag = $request->flag;
                $langue->save();
                return response()->json([
                    "data" => $langue,
                    "message" => trans('messages.saved')
                ], 200);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->checkPermission('Langues', 'read')) {
            $langue = Langues::where('deleted', 0)->find($id);
            if ($langue) {
                return response()->json([
                    "data" => $langue
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

    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required|unique:langues,name," . $id,
            "iso" => "required",
            "flag" => "required"
        ]);
        if (Auth::user()->checkPermission('Langues', 'update')) {
            $langue = Langues::where('deleted', 0)->find($id);
            if ($langue) {
                $langue->name = $request->name;
                $langue->iso = $request->iso;
                $langue->flag = $request->flag;
                $langue->save();
                return response()->json([
                    "message" => trans('messages.updated'),
                    "data" => $langue
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
        if (Auth::user()->checkPermission('Langues', 'delete')) {
            $langue = Langues::where('deleted', 0)->find($id);
            if ($langue) {
                $langue->deleted = 1;
                $langue->save();
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
}
