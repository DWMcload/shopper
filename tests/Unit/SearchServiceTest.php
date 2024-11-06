<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Services\SearchService;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCalculateDistance()
    {
        $postcode = '67890';

        $shop1 = new Shop(['name' => 'Shop X', 'latitude' => 10.10, 'longitude' => 20.20, 'status' => Shop::OPEN, 'max_delivery_distance' => 1000]);
        $shop2 = new Shop(['name' => 'Shop Y', 'latitude' => 10.11, 'longitude' => 20.21, 'status' => Shop::OPEN, 'max_delivery_distance' => 2000]);
        $shops = new Collection([$shop1, $shop2]);

        DB::shouldReceive('selectOne')
            ->once()
            ->with('SELECT latitude, longitude from open_postcode_geo WHERE postcode = ?', [$postcode])
            ->andReturn((object) ['latitude' => 10.00, 'longitude' => 20.00]);

        $service = new SearchService();
        $result = $service->calculateDistance($shops, $postcode);

        $this->assertCount(2, $result);
        $this->assertEquals('Shop X', $result[0]['name']);
        $this->assertEquals(24.55919959021559, $result[0]['distance']);
        $this->assertEquals('Shop Y', $result[1]['name']);
        $this->assertEquals(26.04328790324937, $result[1]['distance']);
    }
}
