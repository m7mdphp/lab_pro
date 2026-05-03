<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CorporateServicesController;
use App\Http\Controllers\DoctorServicesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\PrepareController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sitemap & Robots (no locale)
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $baseUrl = rtrim(config('app.url'), '/');
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /admin/*\n\nSitemap: {$baseUrl}/sitemap.xml\n";
    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('robots');

/*
|--------------------------------------------------------------------------
| Public routes shared between locales
|--------------------------------------------------------------------------
*/
$publicRoutes = function () {
    Route::get('/',                            [HomeController::class,             'index'])->name('home');
    Route::get('/about',                       [AboutController::class,            'index'])->name('about');
    Route::get('/services',                    [ServicesController::class,         'index'])->name('services');
    Route::get('/services/{slug}',             [ServicesController::class,         'show'])->name('services.show');
    Route::get('/tests',                       [TestsController::class,            'index'])->name('tests');
    Route::get('/tests/{slug}',                [TestsController::class,            'show'])->name('tests.show');
    Route::get('/packages',                    [PackagesController::class,         'index'])->name('packages');
    Route::get('/packages/{slug}',             [PackagesController::class,         'show'])->name('packages.show');
    Route::get('/branches',                    [BranchesController::class,         'index'])->name('branches');
    Route::get('/partners',                    [PartnersController::class,         'index'])->name('partners');
    Route::get('/prepare-for-your-tests',      [PrepareController::class,          'index'])->name('prepare');
    Route::get('/contact',                     [ContactController::class,          'index'])->name('contact');
    Route::post('/contact',                    [ContactController::class,          'store'])->name('contact.store');
    Route::get('/book-a-test',                 [BookingController::class,          'index'])->name('booking');
    Route::post('/book-a-test',                [BookingController::class,          'store'])->name('booking.store');

    // New pages
    Route::get('/blog',                        [BlogController::class,             'index'])->name('blog');
    Route::get('/blog/{slug}',                 [BlogController::class,             'show'])->name('blog.show');
    Route::get('/team',                        [TeamController::class,             'index'])->name('team');
    Route::get('/my-results',                  [ResultsController::class,          'index'])->name('results');
    Route::get('/doctor-services',             [DoctorServicesController::class,   'index'])->name('doctor-services');
    Route::get('/corporate-services',          [CorporateServicesController::class,'index'])->name('corporate-services');
};

// English — default, no prefix
Route::middleware('setLocale:en')->group($publicRoutes);

// Arabic — /ar prefix
Route::prefix('ar')->name('ar.')->middleware('setLocale:ar')->group($publicRoutes);
