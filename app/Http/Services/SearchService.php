<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Classes\POI;
use App\Interfaces\SearchServiceInterface;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SearchService implements SearchServiceInterface
{
    public function nearbyStores(string $postcode): array
    {
        $shops = $this->calculateDistance(Shop::all(), $postcode);
        return array_slice($shops, 0, 10);
    }

    public function canDeliverTo(string $postcode): array
    {
        $shops = $this->calculateDistance(Shop::where('status', Shop::OPEN)->get(), $postcode);
        $shops = array_filter($shops, function ($item) {
            return $item["distance"] < $item["max_delivery_distance"];
        });
        return $shops;
    }

    public function calculateDistance(Collection $shops, string $postcode): array
    {
        $postcodeLocation = DB::selectOne(
            'SELECT latitude, longitude from open_postcode_geo WHERE postcode = ?',
            [$postcode]
        );

        $postcodePOI = new POI($postcodeLocation->latitude, $postcodeLocation->longitude);

        foreach ($shops as $shop) {
            $shopPOI = new POI($shop->latitude, $shop->longitude);
            $distance = $postcodePOI->getDistanceInMetersTo($shopPOI);
            $response [] = ["name" => $shop->name, "status" => $shop->status, "distance" => $distance, "max_delivery_distance" => $shop->max_delivery_distance];
        }
        usort($response, function ($a, $b) { return $a['distance'] > $b['distance']; });
        return $response;
    }
}
