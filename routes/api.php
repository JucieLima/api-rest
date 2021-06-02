<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Products Route
Route::prefix("products")->group(function(){
    Route::get("/", [ProductController::class, "index"]);
    Route::post("/", [ProductController::class, "save"]);
    Route::get("/{id}", [ProductController::class, "show"]);
    Route::put("/", [ProductController::class, "update"]);
    Route::delete("/{id}", [ProductController::class, "delete"]);
});

Route::resource('/users', UserController::class);

