<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\SearchRequest;
use App\Http\Services\SearchService;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function __construct(private readonly SearchService $searchService)
    {
    }

    public function searchStores(SearchRequest $request): JsonResponse
    {
        $postcode = $request->input('postcode');
        $shops = $this->searchService->nearbyStores($postcode);
        return ApiResponse::sendResponse($shops, '');
    }

    public function searchDeliveries(SearchRequest $request): JsonResponse
    {
        $postcode = $request->input('postcode');
        $shops = $this->searchService->canDeliverTo($postcode);
        return ApiResponse::sendResponse($shops, '');
    }
}
