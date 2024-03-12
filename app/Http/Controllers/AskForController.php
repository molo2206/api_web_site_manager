<?php

namespace App\Http\Controllers;


use App\Models\AskFor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AskForController extends Controller
{
    public function index()
    {
        return response()->json([
            "data" => AskFor::with('user')->get()
        ], 200);
    }

    public function store(Request $request)
    {
        if ($request->type == 'ask_visa') {
            $request->validate([
                "full_name" => "required",
                "nationalite" => 'required',
                "ministere" => "required",
                "id_service" => "required",
                "passport" => "required",
                "valid_in" => "required",
                "valid_out" => "required",
                "yellow_card" => "required",
                "motif" => "required",
                "extrait_bank" => "required",
                "type" => "required"
            ]);
            $user = Auth::user();
            if ($user->demandes()->where('type', 'ask_travel')->where('status', 'pending')->exists()) {
                $id_service = MethodsController::uploadDoc($request, "ID Service " . $request->full_name . "-" . time(), '/uploads/demandes_doc/', 'id_service');
                $yellow_card = MethodsController::uploadDoc($request, "Yellow Card " . $request->full_name . "-" . time(), '/uploads/demandes_doc/', 'yellow_card');
                $extrait_bank = MethodsController::uploadDoc($request, "Extrait bancaire " . $request->full_name . "-" . time(), '/uploads/demandes_doc/', 'extrait_bank');

                $data = Auth::user()->demandes()->create([
                    "full_name" => $request->full_name,
                    "nationalite" => $request->nationalite,
                    "ministere" => $request->ministere,
                    "id_service" => $id_service,
                    "valid_in" => $request->valid_in,
                    "valid_out" => $request->valid_out,
                    "passport" => $request->passport,
                    "yellow_card" => $yellow_card,
                    "motif" => $request->motif,
                    "extrait_bank" => $extrait_bank,
                    "type" => $request->type
                ]);

                return response()->json([
                    "data" => $data,
                    "message" => trans('messages.saved')
                ], 200);
            } else {
                return response()->json([
                    "message" => "Veuillez d'abord demander un voyage missionnaire avant de demander un visa"
                ], 402);
            }
        } else {
            $request->validate([
                "full_name" => "required",
                "nationalite" => 'required',
                "ministere" => "required",
                "motif" => "required",
                "type" => "required"
            ]);
            $user = Auth::user();
            $data = Auth::user()->demandes()->create([
                "full_name" => $request->full_name,
                "nationalite" => $request->nationalite,
                "ministere" => $request->ministere,
                "motif" => $request->motif,
                "type" => $request->type
            ]);

            return response()->json([
                "data" => $data,
                "message" => trans('messages.saved')
            ], 200);
        }
    }

    public function allVisa()
    {
        return response()->json([
            "data" => AskFor::with('user')->where('type', 'ask_visa')->get()
        ], 200);
    }


    public function allTravel()
    {
        return response()->json([
            "data" => AskFor::with('user')->where('type', 'ask_travel')->get()
        ], 200);
    }

    public function OneRequest($id)
    {
        $demande = AskFor::with('user')->find($id);
        if ($demande) {
            return response()->json([
                "data" => $demande
            ]);
        } else {
            return response()->json([
                "message" => trans('messages.idNotFound')
            ], 404);
        }
    }
}
