<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\UuidInterface;

interface ShopRepositoryInterface
{
    public function index(): Collection;
    public function getById(UuidInterface $id): Shop;
    public function store(array $data): Shop;
    public function update(array $data, UuidInterface $id): void;
    public function delete(UuidInterface $id): void;
}
