<?php

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

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('auth')->name('auth.')->group(function (Router $router) {
    $router->post('login', [AuthController::class, 'login'])->name('login');
    $router->post('register', [AuthController::class, 'register'])->name('register');
});
