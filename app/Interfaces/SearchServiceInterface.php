<?php

declare(strict_types=1);

namespace App\Interfaces;

interface SearchServiceInterface
{
    public function nearbyStores(string $postcode): array;
    public function canDeliverTo(string $postcode): array;
}
