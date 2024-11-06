<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Resources\ShopResource;
use App\Interfaces\ShopRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ShopController extends Controller
{
    public function __construct(private readonly ShopRepositoryInterface $shopRepositoryInterface)
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->shopRepositoryInterface->index();

        return ApiResponse::sendResponse(ShopResource::collection($data), '');
    }

    public function store(StoreShopRequest $request): JsonResponse
    {
        $details = [
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
            'store_type_id' => $request->store_type_id,
            'max_delivery_distance' => $request->max_delivery_distance
        ];

        DB::beginTransaction();
        try {
            $shop = $this->shopRepositoryInterface->store($details);

            DB::commit();

        } catch (\Exception $ex) {
            ApiResponse::rollback($ex);
        }
        return ApiResponse::sendResponse(new ShopResource($shop), 'Shop Creation Successful', 201);
    }

    public function show(string $id): JsonResponse
    {
        $shop = $this->shopRepositoryInterface->getById(Uuid::fromString($id));

        return ApiResponse::sendResponse(new ShopResource($shop), '', 200);
    }

    public function update(UpdateShopRequest $request, string $id): JsonResponse
    {
        $updateDetails = [
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
            'store_type_id' => $request->store_type_id,
            'max_delivery_distance' => $request->max_delivery_distance
        ];
        DB::beginTransaction();
        try {
            $this->shopRepositoryInterface->update($updateDetails, Uuid::fromString($id));
            DB::commit();
        } catch (\Exception $ex) {
            ApiResponse::rollback($ex);
        }
        return ApiResponse::sendResponse('Shop Updating Successful', '', 201);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->shopRepositoryInterface->delete(Uuid::fromString($id));

        return ApiResponse::sendResponse('Shop Deleting Successful', '', 204);
    }
}
