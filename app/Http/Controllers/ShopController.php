<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Resources\ShopResource;
use App\Interfaces\ShopRepositoryInterface;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function __construct(private ShopRepositoryInterface $shopRepositoryInterface)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->shopRepositoryInterface->index();

        return ApiResponse::sendResponse(ShopResource::collection($data),'',200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
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
        try{
            $shop = $this->shopRepositoryInterface->store($details);

            DB::commit();
            return ApiResponse::sendResponse(new ShopResource($shop),'Shop Creation Successful',201);

        } catch(\Exception $ex){
            ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shop = $this->shopRepositoryInterface->getById($id);

        return ApiResponse::sendResponse(new ShopResource($shop),'',200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, $id)
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
        try{
            $this->shopRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ApiResponse::sendResponse('Shop Updating Successful','',201);

        }
        catch(\Exception $ex){
            ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->shopRepositoryInterface->delete($id);

        return ApiResponse::sendResponse('Shop Deleting Successful','',204);
    }
}
