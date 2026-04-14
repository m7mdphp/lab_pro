<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\PrepareController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TestsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes shared between locales
|--------------------------------------------------------------------------
*/
$publicRoutes = function () {
    Route::get('/',                        [HomeController::class,     'index'])->name('home');
    Route::get('/about',                   [AboutController::class,    'index'])->name('about');
    Route::get('/services',                [ServicesController::class, 'index'])->name('services');
    Route::get('/services/{slug}',         [ServicesController::class, 'show'])->name('services.show');
    Route::get('/tests',                   [TestsController::class,    'index'])->name('tests');
    Route::get('/tests/{slug}',            [TestsController::class,    'show'])->name('tests.show');
    Route::get('/packages',                [PackagesController::class, 'index'])->name('packages');
    Route::get('/packages/{slug}',         [PackagesController::class, 'show'])->name('packages.show');
    Route::get('/branches',                [BranchesController::class, 'index'])->name('branches');
    Route::get('/partners',                [PartnersController::class, 'index'])->name('partners');
    Route::get('/prepare-for-your-tests',  [PrepareController::class,  'index'])->name('prepare');
    Route::get('/contact',                 [ContactController::class,  'index'])->name('contact');
    Route::post('/contact',                [ContactController::class,  'store'])->name('contact.store');
    Route::get('/book-a-test',             [BookingController::class,  'index'])->name('booking');
    Route::post('/book-a-test',            [BookingController::class,  'store'])->name('booking.store');
};

// English — default, no prefix
Route::middleware('setLocale:en')->group($publicRoutes);

// Arabic — /ar prefix
Route::prefix('ar')->name('ar.')->middleware('setLocale:ar')->group($publicRoutes);
