<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasUuids;
    use HasFactory;

    public const OPEN = 'open';
    public const CLOSED = 'closed';

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
        'store_type_id',
        'max_delivery_distance'
    ];
}
