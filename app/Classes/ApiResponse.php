<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\ApiResponseInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiResponse implements ApiResponseInterface
{
    public static function rollback(\Exception $e, $message = "An error occured, the process have been rolled back."): void
    {
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw(\Exception $e, string $message = "An unrecoverable error occurred."): void
    {
        Log::info($e);
        throw new HttpResponseException(response()->json(["message" => $message], 500));
    }

    public static function sendResponse(mixed $result, string $message, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }
}
