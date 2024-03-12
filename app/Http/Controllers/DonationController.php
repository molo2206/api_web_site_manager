<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Settings;
use Stripe;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "first_name" => "required",
            "last_name" => "required",
            "country" => "required",
            "state" => "required",
            "city" => "required",
            "email" => "required",
            "phone" => "required",
            "zipcode" => "required",
            "adresse" => "required",
            "amount" => "required",
            "id" => "required"
        ]);
        $setting = Settings::first();
        if ($setting->stripe) {
            try {
                $stripe = new \Stripe\StripeClient($setting->stripe);
                Stripe\Stripe::setApiKey($setting->stripe);

                $response = $stripe->paymentIntents->create([
                    'amount' => $request->amount * 100,
                    'currency' => 'usd',
                    'payment_method' => $request->id,
                    'confirm' => true,
                    'automatic_payment_methods' => ['enabled' => true],
                    "description" => "Receipt of funds from the donation form on the d'ici international website",
                    "return_url" => 'https://diciinternationsl.org'
                ]);

                Donation::create($request->all());

                return response()->json([
                    "message" => "success",
                    "status" => 200,
                    "data" => $response
                ], 200);
            } catch (Exception $ex) {
                return response()->json([
                    "message" => "error " . $ex->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                "message" => "An error occured with secret key"
            ], 403);
        }
    }
}
