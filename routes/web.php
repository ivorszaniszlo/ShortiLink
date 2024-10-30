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
 * These routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great!
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

Route::get('/new', [UrlController::class, 'create'])->name('url.create');
Route::post('/new', [UrlController::class, 'store'])->name('url.store');
Route::get('/jump/{code}', [UrlController::class, 'redirect'])->name('url.redirect');
