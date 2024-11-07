<?php

use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/shops', ShopController::class);
Route::post('/search/stores', [SearchController::class,'searchStores']);
Route::post('/search/deliveries', [SearchController::class,'searchDeliveries']);
