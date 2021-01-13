<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Http\Request;

class OccasionController extends Controller
{
    public function getOccasion(Request $request, Response $response) {
        $http = Http::get('https://www.caravan24.ch/api/rest/?method=dealer&token=d8e7f08d726b970b69a947600139cf19');

        return $response
            ->setContent($http->body())
            ->header('Content-Type', 'application/xml');
    }
}
