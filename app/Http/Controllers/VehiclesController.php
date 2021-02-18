<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Http\Request;

class VehiclesController extends Controller
{
    public function getVehicles(Request $request, Response $response)
    {
        $vehicles = Cache::get('vehicles');

        if (!$vehicles) {
            $http = Http::get('https://www.caravan24.ch/api/rest/?method=dealer&token=d8e7f08d726b970b69a947600139cf19');
            $vehicles = $http->body();

            Cache::add('vehicles', $vehicles, 600);
        }


        return $response
            ->setContent($vehicles)
            ->header('Content-Type', 'application/xml');
    }
}
