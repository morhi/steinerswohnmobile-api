<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Http\Request;

class OccasionController extends Controller
{
    public function getOccasion(Request $request, Response $response)
    {
        $occasion = Cache::get('occasion');

        if (!$occasion) {
            $http = Http::get('https://www.caravan24.ch/api/rest/?method=dealer&token=d8e7f08d726b970b69a947600139cf19');
            $occasion = $http->body();

            Cache::add('occasion', $occasion, 60);
        }


        return $response
            ->setContent($occasion)
            ->header('Content-Type', 'application/xml');
    }
}
