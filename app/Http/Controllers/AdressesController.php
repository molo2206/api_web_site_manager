<?php

namespace App\Http\Controllers;

use App\Models\Adresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdressesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->checkPermission('Settings', 'read')) {
            return response()->json([
                "data" => Adresses::with('country')->where('deleted', 0)->get()
            ], 200);
        } else {
            return response()->json([
                "message" => trans('messages.notAuthorized')
            ], 422);
        }
    }

    public function getAdresse()
    {
        return response()->json([
            "data" => Adresses::with('country')->where('deleted', 0)->get()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "country_id" => "required",
            "city" => "required",
            "emails" => "required",
            "phones" => "required",
            "adresse" => "required"
        ]);
        if (Auth::user()->checkPermission('Settings', 'create')) {

            $adresse = Adresses::create([
                "country_id" => $request->country_id,
                "city" => $request->city,
                "emails" => json_encode($request->emails),
                "phones" => json_encode($request->phones),
                "adresse" => $request->adresse
            ]);
            return response()->json([
                "data" => $adresse,
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
    public function show(Adresses $adresses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adresses $adresses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adresses $adresses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adresses $adresses)
    {
        //
    }
}
