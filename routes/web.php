<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;

Route::get('/', [CatalogController::class, 'home'])->name('home');

Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.submit');

Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/cart',                     [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add',                [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{product}',   [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear',              [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/cart/checkout',            [CartController::class, 'confirm'])->name('cart.confirm');
    Route::post('/cart/checkout',           [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/orders/{order}/success',   [CartController::class, 'success'])
        ->name('orders.success')
        ->whereNumber('order');
});

Route::middleware([\App\Http\Middleware\EnsureSeller::class])->group(function () {
    Route::get('/seller',                        [SellerController::class, 'dashboard'])->name('seller.dashboard');
    Route::post('/seller/products',              [SellerController::class, 'storeProduct'])->name('seller.products.store');
    Route::put('/seller/products/{id}',          [SellerController::class, 'updateProduct'])->name('seller.products.update');
    Route::delete('/seller/products/{id}',       [SellerController::class, 'destroyProduct'])->name('seller.products.destroy');

    Route::get('/seller/orders',                 [SellerController::class, 'orders'])->name('seller.orders');

    Route::get('/seller/category/{category}',    [SellerController::class, 'category'])
        ->where('category', 'pastry|cake|bread|dessert_box')
        ->name('seller.category');
});

Route::get('/category/{category}', [CatalogController::class, 'index'])
    ->where('category', 'pastry|cake|bread|dessert_box')
    ->name('catalog.category');

Route::fallback(function () {
    return response('Page not found', 404);
});

Route::middleware('auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});



Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('auth');
