<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class VehiclesController extends Controller
{
    public const HOUR_IN_SECONDS = 3600;

    public function getVehicles(Response $response)
    {
        $vehicles = Cache::remember('vehicles', self::HOUR_IN_SECONDS, function () {
            $http = Http::get('https://www.caravan24.ch/api/rest/?method=dealer&token=d8e7f08d726b970b69a947600139cf19');

            return $http->body();
        });

        return $response
            ->setContent($vehicles)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Get the detail data for a single vehicle.
     *
     * @param  Response  $response
     * @param  int  $id
     * @return Response
     */
    public function getVehicle(Response $response, int $id)
    {
        $url = 'https://www.caravan24.ch/chde/wohnmobile/detail/'.$id;
        $key = 'vehicle_detail_'.md5($url);

        $body = Cache::remember($key, self::HOUR_IN_SECONDS, function () use ($url) {
            $http = Http::get($url);

            return base64_encode($http->body());
        });

        try {
            $doc = new DOMDocument();
            @$doc->loadHTML(base64_decode($body));
            $path = new DOMXPath($doc);
        } catch (\Exception $e) {
            return $response->setStatusCode(404);
        }

        $rentPrice = $path->query('//p[@class="rentprice"]')->item(0)->textContent ?? null;
        $rentalTable = $doc->saveHTML($path->query('//table[@class="rental"]')->item(0)) ?? null;

        return $response->setContent([
            'id' => $id,
            'rent_price' => $rentPrice,
            'rental_table' => $rentalTable
        ]);
    }
}
