<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Controllers\ShopController;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Interfaces\ShopRepositoryInterface;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Mockery;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $shopRepositoryInterface;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shopRepositoryInterface = Mockery::mock(ShopRepositoryInterface::class);
        $this->app->instance(ShopRepositoryInterface::class, $this->shopRepositoryInterface);
    }

    public function testIndex()
    {
        $shopData = Shop::factory()->count(3)->make();

        $this->shopRepositoryInterface->shouldReceive('index')
            ->once()
            ->andReturn($shopData);

        $response = (new ShopController($this->shopRepositoryInterface))->index();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
    }

    public function testStore()
    {
        $requestData = [
            'name' => 'Shop 1',
            'latitude' => 12.34,
            'longitude' => 56.78,
            'status' => 1,
            'store_type_id' => 2,
            'max_delivery_distance' => 10,
        ];

        $storeShopRequest = Mockery::mock(StoreShopRequest::class);
        $storeShopRequest->shouldReceive('all')->andReturn($requestData);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $shop = new Shop($requestData);
        $this->shopRepositoryInterface->shouldReceive('store')
            ->once()
            ->with($requestData)
            ->andReturn($shop);

        $response = (new ShopController($this->shopRepositoryInterface))->store($storeShopRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->status());
    }

    public function testStoreHandlesException()
    {
        $requestData = [
            'name' => 'Shop 1',
            'latitude' => 12.34,
            'longitude' => 56.78,
            'status' => 1,
            'store_type_id' => 2,
            'max_delivery_distance' => 10,
        ];

        $storeShopRequest = Mockery::mock(StoreShopRequest::class);
        $storeShopRequest->shouldReceive('all')->andReturn($requestData);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $this->shopRepositoryInterface->shouldReceive('store')
            ->once()
            ->with($requestData)
            ->andThrow(new \Exception());
        $this->expectException(\Exception::class);
        (new ShopController($this->shopRepositoryInterface))->store($storeShopRequest);
    }

    public function testShow()
    {
        $shopId = Uuid::uuid4();
        $shop = new Shop(['id' => $shopId]);
        $this->shopRepositoryInterface->shouldReceive('getById')
            ->once()
            ->with($shopId->toString())
            ->andReturn($shop);

        $response = (new ShopController($this->shopRepositoryInterface))->show($shopId->toString());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
    }

    public function testUpdate()
    {
        $updateData = [
            'name' => 'Updated Shop',
            'latitude' => 12.34,
            'longitude' => 56.78,
            'status' => 1,
            'store_type_id' => 2,
            'max_delivery_distance' => 15,
        ];

        $updateId = UUid::uuid4()->toString();

        $updateShopRequest = Mockery::mock(UpdateShopRequest::class);
        $updateShopRequest->shouldReceive('all')->andReturn($updateData);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $this->shopRepositoryInterface->shouldReceive('update')
            ->once()
            ->with($updateData, $updateId);

        $response = (new ShopController($this->shopRepositoryInterface))->update($updateShopRequest, $updateId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->status());
    }

    public function testUpdateHandlesException()
    {
        $updateData = [
            'name' => 'Updated Shop',
            'latitude' => 12.34,
            'longitude' => 56.78,
            'status' => 1,
            'store_type_id' => 2,
            'max_delivery_distance' => 15,
        ];

        $updateShopRequest = Mockery::mock(UpdateShopRequest::class);
        $updateShopRequest->shouldReceive('all')->andReturn($updateData);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $this->shopRepositoryInterface->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        (new ShopController($this->shopRepositoryInterface))->update($updateShopRequest, UUid::uuid4()->toString());
    }

    public function testDestroy()
    {
        $deleteId = UUid::uuid4()->toString();
        $this->shopRepositoryInterface->shouldReceive('delete')
            ->once()
            ->with($deleteId);

        $response = (new ShopController($this->shopRepositoryInterface))->destroy($deleteId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(204, $response->status());
    }
}
