<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Controllers\SearchController;
use App\Http\Requests\SearchRequest;
use App\Http\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    protected $searchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchService = Mockery::mock(SearchService::class);
        $this->app->instance(SearchService::class, $this->searchService);
    }

    public function testSearchStores()
    {
        $searchRequest = Mockery::mock(SearchRequest::class);
        $searchRequest->shouldReceive('input')
            ->once()
            ->with('postcode')
            ->andReturn('12345');

        $shops = ['shop1', 'shop2'];

        $this->searchService->shouldReceive('nearbyStores')
            ->once()
            ->with('12345')
            ->andReturn($shops);

        $response = (new SearchController($this->searchService))->searchStores($searchRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($shops, $response->getData()->data);
    }

    public function testSearchDeliveries()
    {
        $searchRequest = Mockery::mock(SearchRequest::class);
        $searchRequest->shouldReceive('input')
            ->once()
            ->with('postcode')
            ->andReturn('54321');

        $shops = ['shopA', 'shopB'];

        $this->searchService->shouldReceive('canDeliverTo')
            ->once()
            ->with('54321')
            ->andReturn($shops);

        $response = (new SearchController($this->searchService))->searchDeliveries($searchRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($shops, $response->getData()->data);
    }
}
