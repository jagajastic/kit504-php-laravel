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

use App\Enums\UserType;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ShopController;
use App\Http\Middleware\UserTypeMiddleware;

// Authentication Routes.
Route::prefix('auth')->name('auth.')->group(function (Router $router) {
    $router->post('login', [AuthController::class, 'login'])->name('login');
    $router->post('register', [AuthController::class, 'register'])->name('register');
});

// Shop Routes.
Route::prefix('shops')->name('shops.')->group(function (Router $router) {
    $router->get('/', [ShopController::class, 'index'])->name('shops.index');
    $router->post('/', [ShopController::class, 'store'])
        ->name('shops.store')
        ->middleware(UserTypeMiddleware::make([UserType::DIRECTOR]));
    $router->get('/{shop}', [ShopController::class, 'show'])->name('shops.show');
    $router->patch('/{shop}', [ShopController::class, 'update'])->name('shops.update');
    $router->delete('/{shop}', [ShopController::class, 'destroy'])->name('shops.destroy');
    $router->get('/{shop}/products', [ShopController::class, 'productsIndex'])->name('shops.products.index');
    $router->get('/{shop}/products/{product}', [ShopController::class, 'productsShow'])->name('shops.products.show');
    $router->patch('/{shop}/products', [ShopController::class, 'productsUpdate'])->name('shops.products.update');
});

// Product Routes.
Route::prefix('products')
    ->name('products.')
    ->middleware([
        'auth:api',
        UserTypeMiddleware::make([UserType::DIRECTOR]),
    ])
    ->group(function (Router $router) {
        $router->get('/', [ProductController::class, 'index'])->name('products.index');
        $router->post('/', [ProductController::class, 'store'])->name('products.store');
        $router->get('/{product}', [ProductController::class, 'show'])->name('products.show');
        $router->patch('/{product}', [ProductController::class, 'update'])->name('products.update');
        $router->delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
