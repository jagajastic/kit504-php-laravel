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
use App\Http\Middleware\UserTypeMiddleware;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\ShopController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;

// Authentication Routes.
Route::prefix('auth')->name('auth.')->group(function (Router $router) {
    $router->post('login', [AuthController::class, 'login'])->name('login');
    $router->post('register', [AuthController::class, 'register'])->name('register');
    $router->get('user', [AuthController::class, 'user'])->name('user');
    $router->patch('user', [AuthController::class, 'updateUser'])->name('updateUser');
});

// Cart Routes.
Route::prefix('carts')
    ->name('carts.')
    ->middleware([
        'auth:api',
        UserTypeMiddleware::make([
            UserType::SHOP_STAFF,
            UserType::UTAS_EMPLOYEE,
            UserType::UTAS_STUDENT,
        ]),
    ])
    ->group(function (Router $router) {
        $router->get('/{shop}', [CartController::class, 'show'])->name('show');
        $router->post('/{shop}', [CartController::class, 'store'])->name('store');
        $router->delete('/{shop}', [CartController::class, 'clear'])->name('clear');
        $router->patch('/{shop}/{productId}', [CartController::class, 'update'])->name('update');
        $router->delete('/{shop}/{productId}', [CartController::class, 'destroy'])->name('destroy');
    });

// Shop Routes.
Route::prefix('shops')->name('shops.')->group(function (Router $router) {
    $router->get('/', [ShopController::class, 'index'])->name('index');
    $router->post('/', [ShopController::class, 'store'])
        ->name('store')
        ->middleware(UserTypeMiddleware::make([UserType::DIRECTOR]));
    $router->get('/{shop}', [ShopController::class, 'show'])->name('show');
    $router->patch('/{shop}', [ShopController::class, 'update'])->name('update');
    $router->delete('/{shop}', [ShopController::class, 'destroy'])->name('destroy');
    $router->get('/{shop}/products', [ShopController::class, 'productsIndex'])->name('products.index');
    $router->get('/{shop}/products/{product}', [ShopController::class, 'productsShow'])->name('products.show');
    $router->put('/{shop}/products', [ShopController::class, 'productsUpdate'])->name('products.update');
});

// Product Routes.
Route::prefix('products')
    ->name('products.')
    ->middleware([
        'auth:api',
        UserTypeMiddleware::make([UserType::DIRECTOR]),
    ])
    ->group(function (Router $router) {
        $router->get('/', [ProductController::class, 'index'])->name('index');
        $router->post('/', [ProductController::class, 'store'])->name('store');
        $router->get('/{product}', [ProductController::class, 'show'])->name('show');
        $router->patch('/{product}', [ProductController::class, 'update'])->name('update');
        $router->delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

// Order Routes.
Route::prefix('orders')
    ->name('orders.')
    ->middleware(['auth:api'])
    ->group(function (Router $router) {
        $router->get('/', [OrderController::class, 'index'])->name('index');
        $router->post('/', [OrderController::class, 'store'])->name('store')->middleware(
            UserTypeMiddleware::make([
                UserType::SHOP_STAFF,
                UserType::UTAS_EMPLOYEE,
                UserType::UTAS_STUDENT,
            ])
        );
        $router->get('/{order}', [OrderController::class, 'show'])->name('show');
    });
