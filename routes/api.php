<?php

use App\Http\Controllers\AdressesController;
use App\Http\Controllers\AskForController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LanguesController;
use App\Http\Controllers\LivresController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\permissionController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestimonialsController;
use App\Http\Controllers\useController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['localization']], function () {

    Route::group(['middleware' => ['auth:sanctum',]], function () {
        Route::get('/auth/show-profile', [AuthController::class, '_showProfile']);
        Route::put('/auth/update-profile', [AuthController::class, '_editProfile']);
        Route::put('/auth/update-password', [AuthController::class, '_editPassword']);
        Route::post('/auth/update-photo', [AuthController::class, '_editPhoto']);

        //roles routes

        Route::get('/roles', [roleController::class, 'index']);
        Route::get('/roles/{id}', [roleController::class, 'edit']);
        Route::post('/roles', [roleController::class, 'store']);
        Route::put('/roles/{id}', [roleController::class, 'update']);
        Route::put('/roles/status/{id}', [roleController::class, 'status']);
        Route::delete('/roles/{id}', [roleController::class, 'destroy']);
        Route::post('/assign-permissions', [roleController::class, 'assignPermissions']);
        //permissions routes

        Route::get('/permissions', [permissionController::class, 'index']);
        Route::get('/get_resources', [permissionController::class, 'get_resources']);
        Route::get('/permissions/{id}', [permissionController::class, 'edit']);
        Route::post('/permissions', [permissionController::class, 'store']);
        Route::put('/permissions/{id}', [permissionController::class, 'update']);
        Route::put('/permissions/status/{id}', [permissionController::class, 'status']);
        Route::delete('/permissions/{id}', [permissionController::class, 'destroy']);

        //langues
        Route::get('/langues', [LanguesController::class, 'index']);
        Route::post('/langues', [LanguesController::class, 'store']);
        Route::get('/langues/{id}', [LanguesController::class, 'edit']);
        Route::put('/langues/{id}', [LanguesController::class, 'update']);
        Route::delete('/langues/{id}', [LanguesController::class, 'destroy']);

        //categories
        Route::get('/category', [CategoryController::class, 'index']);
        Route::post('/category', [CategoryController::class, 'store']);
        Route::get('/category/{id}', [CategoryController::class, 'edit']);
        Route::put('/category/{id}', [CategoryController::class, 'update']);
        Route::put('/category/status/{id}', [CategoryController::class, 'status']);
        Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

        //countries
        Route::get('/country', [CountriesController::class, 'index']);
        Route::post('/country', [CountriesController::class, 'store']);
        Route::get('/country/{id}', [CountriesController::class, 'edit']);
        Route::put('/country/{id}', [CountriesController::class, 'update']);
        Route::put('/country/status/{id}', [CountriesController::class, 'status']);
        Route::delete('/country/{id}', [CountriesController::class, 'destroy']);

        Route::get('/city', [CountriesController::class, 'getCities']);
        Route::post('/city', [CountriesController::class, 'storeCity']);
        Route::put('/city/{id}', [CountriesController::class, 'updateCity']);
        Route::put('/city/status/{id}', [CountriesController::class, 'statusCity']);
        Route::delete('/city/{id}', [CountriesController::class, 'destroyCity']);

        //utilisateurs

        Route::get('/users', [useController::class, 'index']);
        Route::get('/member', [useController::class, 'getUserType']);
        Route::post('/users', [useController::class, 'store']);
        Route::get('/users/{id}', [useController::class, 'edit']);
        Route::put('/users/{id}', [useController::class, 'update']);
        Route::put('/users/status/{id}', [useController::class, 'status']);
        Route::delete('/users/{id}', [useController::class, 'destroy']);

        //settings


        Route::post('/settings/about-us', [SettingsController::class, 'storeAbout']);
        Route::post('/settings/setting', [SettingsController::class, 'storeSettings']);
        Route::post('/settings/logo', [SettingsController::class, 'storeLogos']);
        Route::post('/settings/images', [SettingsController::class, 'storeImages']);

        //adresses

        Route::get('/adresses', [AdressesController::class, 'index']);
        Route::post('/adresses', [AdressesController::class, 'store']);



        //team

        Route::get('/team', [TeamController::class, 'index']);
        Route::post('/team', [TeamController::class, 'store']);
        Route::get('/team/{id}', [TeamController::class, 'edit']);
        Route::post('/team/{id}', [TeamController::class, 'update']);
        Route::put('/team/{id}', [TeamController::class, 'status']);
        Route::delete('/team/{id}', [TeamController::class, 'destroy']);

        //events

        Route::get('/events', [EventController::class, 'index']);
        Route::post('/events', [EventController::class, 'store']);
        Route::get('/events/{id}', [EventController::class, 'edit']);
        Route::post('/events/{id}', [EventController::class, 'update']);
        Route::put('/events/{id}', [EventController::class, 'status']);
        Route::delete('/events/{id}', [EventController::class, 'destroy']);

        //services

        Route::get('/services', [ServicesController::class, 'index']);
        Route::post('/services', [ServicesController::class, 'store']);
        Route::get('/services/{id}', [ServicesController::class, 'edit']);
        Route::post('/services/{id}', [ServicesController::class, 'update']);
        Route::put('/services/{id}', [ServicesController::class, 'status']);
        Route::delete('/services/{id}', [ServicesController::class, 'destroy']);

        //books
        Route::get('/books', [LivresController::class, 'index']);

        Route::post('/books', [LivresController::class, 'store']);
        Route::get('/books/{id}', [LivresController::class, 'edit']);
        Route::post('/books/{id}', [LivresController::class, 'update']);
        Route::put('/books/{id}', [LivresController::class, 'status']);
        Route::delete('/books/{id}', [LivresController::class, 'destroy']);

        Route::get('/testimonials', [TestimonialsController::class, 'index']);
        Route::post('/testimonials', [TestimonialsController::class, 'store']);
        Route::get('/testimonials/{id}', [TestimonialsController::class, 'edit']);
        Route::post('/testimonials/{id}', [TestimonialsController::class, 'update']);
        Route::put('/testimonials/{id}', [TestimonialsController::class, 'status']);
        Route::delete('/testimonials/{id}', [TestimonialsController::class, 'destroy']);

        //dashboard
        Route::get('/dashboard/statistic', [DashboardController::class, 'statistic']);


        Route::post('/media', [MediaController::class, 'store']);
        Route::post('/media/{id}', [MediaController::class, 'update']);

        //ask for something
        Route::get('/demandes', [AskForController::class, 'index']);
        Route::post('/demandes', [AskForController::class, 'store']);

        Route::get('/all-visa-requests', [AskForController::class, 'allVisa']);
        Route::get('/all-travel-requests', [AskForController::class, 'allTravel']);
        Route::get('/demande/{id}', [AskForController::class, 'oneRequest']);
    });
    Route::post('/auth/get-link', [AuthController::class, '_getLink']);
    Route::post('/auth/check-link/{token}', [AuthController::class, '_checkLink']);
    Route::post('/auth/login', [AuthController::class, '_login']);
    Route::get('/category/{type}/public', [CategoryController::class, 'byType']);
    Route::get('/country/public/list', [CountriesController::class, 'publicList']);
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/public/adresses', [AdressesController::class, 'getAdresse']);
    Route::get('/public/events', [EventController::class, 'getEvents']);
    Route::get('/public/services', [ServicesController::class, 'getServices']);
    Route::get('/public/{service}/services', [ServicesController::class, 'getServicesById']);
    Route::get('/public/{event}/events', [EventController::class, 'getEventsById']);
    Route::get('/public/events/{category}', [EventController::class, 'getEventsCategory']);
    Route::get('/public/langues', [LanguesController::class, 'getLanguages']);
    Route::get('/public/team', [TeamController::class, 'getTeam']);
    Route::get('/public/books', [LivresController::class, 'getBooks']);
    Route::get('/public/testimonials', [TestimonialsController::class, 'getTestimonials']);
    Route::get('/media/{type}', [MediaController::class, 'index']);
    Route::post('/donate', [DonationController::class, 'store']);
    Route::post('/contact', [SettingsController::class, 'contact']);
});
