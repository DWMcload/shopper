<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ShopRepositoryInterface;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\UuidInterface;

class ShopRepository implements ShopRepositoryInterface
{
    public function index(): Collection
    {
        return Shop::all();
    }

    public function getById(UuidInterface $id): Shop
    {
        return Shop::findOrFail($id);
    }

    public function store(array $data): Shop
    {
        return Shop::create($data);
    }

    public function update(array $data, UuidInterface $id): void
    {
        Shop::whereId($id)->update($data);
    }

    public function delete(UuidInterface $id): void
    {
        Shop::destroy($id);
    }
}
