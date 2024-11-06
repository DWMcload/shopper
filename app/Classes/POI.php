<?php

declare(strict_types=1);

namespace App\Classes;

/**
 * Haversine formulae implementation from https://rosettacode.org/wiki/Haversine_formula#PHP
 */

class POI
{
    private float $latitude;
    private float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = deg2rad($latitude);
        $this->longitude = deg2rad($longitude);
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getDistanceInMetersTo(POI $other): float
    {
        $radiusOfEarth = 6371; // Earth's radius in kilometers.

        $diffLatitude = $other->getLatitude() - $this->latitude;
        $diffLongitude = $other->getLongitude() - $this->longitude;

        $a = sin($diffLatitude / 2) ** 2 +
            cos($this->latitude) *
            cos($other->getLatitude()) *
            sin($diffLongitude / 2) ** 2;

        $c = 2 * asin(sqrt($a));
        $distance = $radiusOfEarth * $c;

        return $distance;
    }
}
