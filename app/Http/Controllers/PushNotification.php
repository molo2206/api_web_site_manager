<?php

namespace App\Http\Controllers;

use Google_Client;
use Illuminate\Http\Request;


class PushNotification extends Controller
{
    public function sendPushNotification()
    {

        $credentialsFilePath = public_path() . "/firebase/fcm.json";
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $apiurl = 'https://fcm.googleapis.com/v1/projects/cosamed-1e86c/messages:send';
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];
        $test_data = [
            "title" => "TITLE_HERE",
            "description" => "DESCRIPTION_HERE",
        ];

        $data['data'] =  $test_data;

        $data['token'] = 'ciyk_tg1QOaKrITprZjtWA:APA91bF3Rg0j3Sdq_dDO2y0jDCM4SEEYzyLvVJw3dMqIWrEhZsh6MLEMRVAAxv6dqFSa2EJBM7p0N63IntpZfRJxR3ANguutvbwatTjfoqxqxjiqi4BKHvOxY_KgI1f4-qvYww-na5h3'; // Retrive fcm_token from users table

        $payload['message'] = $data;
        $payload = json_encode($payload);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiurl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_exec($ch);
        $res = curl_close($ch);

        return response()->json([
            'message' => 'Notification has been Sent'
        ]);

        // return $credentialsFilePath;
    }
}
