<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->checkPermission('Countries', 'read')) {
            return response()->json([
                "data" => Countries::with('city')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
    public function getCities()
    {
        if (Auth::user()->checkPermission('Countries', 'read')) {
            return response()->json([
                "data" => City::with('country')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function publicList()
    {
        return response()->json([
            "data" => Countries::with('city')->where('deleted', 0)->where('status', 1)->get()
        ], 200);
    }

    public function publicCities()
    {
        return response()->json([
            "data" => City::where('deleted', 0)->where('status', 1)->get()
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
            "name" => "required",
            "code" => "required"
        ]);
        if (Auth::user()->checkPermission('Countries', 'create')) {
            $country = Countries::where('name', $request->name)->where('deleted', 0)->first();
            if ($country) {
                $country->name = $request->name;
                $country->code = $request->code;
                $country->save();
                return response()->json([
                    "data" => $country,
                    "message" => trans('messages.updated')
                ], 200);
            } else {
                $country = new Countries();
                $country->name = $request->name;
                $country->code = $request->code;
                $country->save();
                return response()->json([
                    "data" => $country,
                    "message" => trans('messages.saved')
                ], 200);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }
    public function storeCity(Request $request)
    {
        $request->validate([
            "name" => "required",
            "country_id" => "required"
        ]);
        if (Auth::user()->checkPermission('Countries', 'create')) {
            $country = Countries::where('deleted', 0)->find($request->country_id);
            if ($country) {
                if ($country->city()->where('name', $request->name)->exists()) {
                    return response()->json([
                        "message" => trans('messages.elementExists')
                    ], 422);
                }
                $country->city()->create([
                    'name' => $request->name
                ]);
                return response()->json([
                    "message" => trans('messages.saved')
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

    /**
     * Display the specified resource.
     */
    public function show(Countries $countries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Auth::user()->checkPermission('Countries', 'read')) {
            $country = Countries::where('deleted', 0)->find($id);
            if ($country) {
                return response()->json([
                    "data" => $country
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.notFound')
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

    public function updateCity(Request $request, $id)
    {
        $request->validate([
            "name" => "required",
            "country_id" => "required"
        ]);
        if (Auth::user()->checkPermission('Countries', 'update')) {
            $country = Countries::where('deleted', 0)->find($request->country_id);
            if ($country) {
                $city = City::where('deleted', 0)->find($id);
                if ($city) {
                    if ($country->city()->where('name', $request->name)->where('id', '!=', $city->id)->exists()) {
                        return response()->json([
                            "message" => trans('messages.elementExists')
                        ], 422);
                    }
                    $city->update([
                        'name' => $request->name,
                        "country_id" => $request->country_id
                    ]);
                    return response()->json([
                        "message" => trans('messages.updated')
                    ], 200);
                } else {
                    return response()->json([
                        "message" => trans('messages.idNotFound')
                    ], 404);
                }
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

    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required|unique:countries,name," . $id,
            "code" => "required|unique:countries,code," . $id,
        ]);
        if (Auth::user()->checkPermission('Countries', 'update')) {
            $country = Countries::find($id);
            if ($country) {
                $country->name = $request->name;
                $country->code = $request->code;
                $country->save();
                return response()->json([
                    "message" => trans('messages.updated'),
                    "data" => $country
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.idnotFound')
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
        if (Auth::user()->checkPermission('Countries', 'delete')) {
            $country = Countries::where('deleted', 0)->find($id);
            if ($country) {
                $country->deleted = 1;
                $country->save();
                return response()->json([
                    "message" => trans('messages.deleted'),
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.notFound')
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
        if (Auth::user()->checkPermission('Countries', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $Countries = Countries::where('deleted', 0)->find($id);
            if ($Countries) {
                $Countries->update([
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

    public function destroyCity($id)
    {
        if (Auth::user()->checkPermission('Countries', 'delete')) {
            $City = City::where('deleted', 0)->find($id);
            if ($City) {
                $City->deleted = 1;
                $City->save();
                return response()->json([
                    "message" => trans('messages.deleted'),
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('messages.notFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function statusCity(Request $request, $id)
    {
        if (Auth::user()->checkPermission('Countries', 'update')) {
            $request->validate([
                "status" => 'required'
            ]);
            $City = City::where('deleted', 0)->find($id);
            if ($City) {
                $City->update([
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
