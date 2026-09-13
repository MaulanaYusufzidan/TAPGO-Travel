<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return response('Halaman login menyusul di fase Authentication. Silakan login manual via tinker/seeder untuk testing.', 200);
})->name('login');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination}', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');

Route::middleware('auth')->group(function () {
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/checkout', [BookingController::class, 'checkout'])->name('checkout.show');
    Route::post('/checkout/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
});

Route::post('/webhooks/midtrans', [PaymentController::class, 'handleMidtransCallback'])->name('webhooks.midtrans');
