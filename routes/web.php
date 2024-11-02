<?php

/**
 * Web Routes
 *
 * @category Routing
 * @package  App\Http
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 *
 * Here is where you can register web routes for your application.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Livewire\UrlShortenerForm;


// Main welcome page route
Route::get(
    '/', 
    function () {
        return view('welcome');
    }
)->name('home');

// Form to create a new shortened URL using the Livewire component
Route::get('/new', UrlShortenerForm::class)->name('url.create');

// Store the new shortened URL from the form
Route::post('/new', [UrlController::class, 'store'])->name('url.store');

// Redirect to the original URL based on the shortened code
Route::get('/jump/{code}', [UrlController::class, 'redirect'])->name('url.redirect');
