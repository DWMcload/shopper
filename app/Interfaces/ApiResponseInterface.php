<?php

declare(strict_types=1);

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

interface ApiResponseInterface
{
    public static function rollback(\Exception $e, string $message): void;
    public static function throw(\Exception $e, string $message): void;
    public static function sendResponse(mixed $result, string $message, int $code): JsonResponse;
}
