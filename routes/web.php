<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\PageController;
use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredDestinations = collect();
    $featuredTrips = collect();

    // Keep the marketing page available during a temporary database outage.
    // In normal operation these are populated from the existing marketplace data.
    try {
        $featuredDestinations = Destination::published()->featured()->take(6)->get();
        $featuredTrips = Trip::with(['destination', 'images'])->published()->featured()->take(6)->get();
    } catch (\Illuminate\Database\QueryException) {
        // The view has intentional empty states for this condition.
    }

    return view('home', [
        'featuredDestinations' => $featuredDestinations,
        'featuredTrips' => $featuredTrips,
    ]);
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination}', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
Route::get('/hotels', [PageController::class, 'hotels'])->name('hotels.index');
Route::get('/hotels/{slug}', [PageController::class, 'hotel'])->name('hotels.show');
Route::get('/flights', [PageController::class, 'flights'])->name('flights.index');
Route::get('/flights/{id}', [PageController::class, 'flight'])->name('flights.show');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/career', [PageController::class, 'career'])->name('career');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/profile', [PageController::class, 'profile'])->middleware('auth')->name('profile');
Route::patch('/profile', [PageController::class, 'updateProfile'])->middleware('auth')->name('profile.update');
Route::patch('/profile/password', [PageController::class, 'updatePassword'])->middleware('auth')->name('profile.password');

Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/checkout', [BookingController::class, 'checkout'])->name('checkout.show');
    Route::post('/checkout/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
    Route::post('/bookings/{booking}/verify-payment', [PaymentController::class, 'verifyStatus'])->name('payments.verify');
    Route::get('/bookings/{booking}/ticket', [TicketController::class, 'show'])->name('bookings.ticket');
    Route::get('/bookings/{booking}/invoice', [InvoiceController::class, 'show'])->name('bookings.invoice');
});

Route::post('/webhooks/midtrans', [PaymentController::class, 'handleMidtransCallback'])->name('webhooks.midtrans');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::controller(\App\Http\Controllers\Admin\DestinationController::class)->prefix('destinations')->name('destinations.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{destination:id}/edit', 'edit')->name('edit');
        Route::put('/{destination:id}', 'update')->name('update');
        Route::delete('/{destination:id}', 'destroy')->name('destroy');
    });

    Route::controller(\App\Http\Controllers\Admin\TripController::class)->prefix('trips')->name('trips.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{trip:id}/edit', 'edit')->name('edit');
        Route::put('/{trip:id}', 'update')->name('update');
        Route::delete('/{trip:id}', 'destroy')->name('destroy');
    });

    Route::resource('schedules', \App\Http\Controllers\Admin\ScheduleController::class)->except('show');

    Route::controller(\App\Http\Controllers\Admin\BookingController::class)->prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{booking}', 'show')->name('show');
        Route::patch('/{booking}/status', 'updateStatus')->name('update-status');
        Route::post('/{booking}/cancel', 'cancel')->name('cancel');
    });

    Route::controller(\App\Http\Controllers\Admin\PaymentController::class)->prefix('payments')->name('payments.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{payment}', 'show')->name('show');
        Route::post('/{payment}/verify', 'verify')->name('verify');
    });

    Route::controller(\App\Http\Controllers\Admin\CustomerController::class)->prefix('customers')->name('customers.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{customer}', 'show')->name('show');
    });
});
