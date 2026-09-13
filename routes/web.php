<?php

use App\Http\Controllers\DestinationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
