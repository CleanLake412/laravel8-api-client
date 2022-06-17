<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiGatewayController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        $server = $request->get('server');
        $protocol = $request->get('protocol');
        $apiKey = $request->get('apiKey');
        $params = $request->get('params');

        $url = $server . $protocol . '/';

        //$httpClient = Http::withToken('Apikey ' . $apiKey);
        $httpClient = Http::withHeaders([
            'Authorization' => 'Apikey ' . $apiKey
        ]);
        $response = $httpClient->post($url, $params);

        return $response;
        //return response()->json();
    }
}
