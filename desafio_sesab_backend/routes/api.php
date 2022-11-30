<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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


Route::controller(UserController::class)->group(
    function () {
        Route::get('/users', 'index');
        Route::get('/user/{user}', 'show');
        Route::post('/user', 'store');
        Route::put('/user/{user}', 'update');
        Route::delete('/user/{user}', 'destroy');
        Route::delete('/address/{address}', 'destroyAddress');
    }
);


Route::controller(RoleController::class)->group(
    function () {
        Route::get('/profiles', 'index');
    }
);