<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RealStateController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RealStatePhotoController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\RealStateSearchController;

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


Route::prefix('v1')->group(function () {
    Route::group(['middleware' => ['jwt.auth']], function(){
        Route::resource('/real-states', RealStateController::class);
        Route::resource('/users', UserController::class);
        Route::resource('/categories', CategoryController::class);
        Route::get('/categories/{id}/real-states', [CategoryController::class, 'realStates']);
        Route::prefix('photos')->group(function(){
            Route::delete('/{id}', [RealStatePhotoController::class, 'remove']);
            Route::put('/set-thumb/{photoId}/{realStateId}', [RealStatePhotoController::class, 'setThumb']);
        });
        Route::get('logout', [LoginController::class, 'logout']);
        Route::get('refresh', [LoginController::class, 'refresh']);
    });
    Route::post('login', [LoginController::class, 'login']);
    Route::get('search', [RealStateSearchController::class, 'index']);
});
