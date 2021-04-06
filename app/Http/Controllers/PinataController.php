<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PinataController extends Controller
{
    public function generateJWT()
    {
        $url = 'https://api.pinata.cloud/users/generateApiKey';
        $data = [
            'keyName' => 'temp_key',
            'maxUses' => 10,
            'permissions' => [
                'endpoints' => [
                    'pinning' => [
                        'pinFileToIPFS' => true,
                        'pinJSONToIPFS' => true
                    ]
                ]
            ]
        ];
        $headers = array(
            'pinata_api_key: ' . config('app.pinata_key'),
            'pinata_secret_api_key: ' . config('app.pinata_secret'),
            'Content-Type: application/json',
            'Accept: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  //Post Fields
        $ret = curl_exec($ch);
        curl_close($ch);
        return response()->json(json_decode($ret, true));
    }
}
