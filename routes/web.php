<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

// Routing
Auth::routes();

// Home page Routes
Route::get('/', [HomeController::class, 'index']);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('admin/home', [HomeController::class, 'index'])->name('admin.home')->middleware('is_admin');

// About Page Routes 
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Trade Place page Routes
Route::resource('trade', 'TradeController');
Route::get('/trade', [TradeController::class, 'index'])->name('trade');
Route::post('/rating/{advertisement}', 'TradeController@advertisementRating')->name('advertisementRating');

// Favourites page Routes
Route::resource('my-favourites', 'FavouriteController');
Route::get('/my-favourites', [FavouriteController::class, 'index'])->name('my-favourites');

// Bookings page Routes
Route::resource('bookings', 'BookingController');
Route::get('/my-bookings', [BookingController::class, 'index'])->name('my-bookings');
Route::get('/bookings/create/', 'BookingController@create')->name('bookings.create');
Route::post('bookings', 'BookingController@store')->name('bookings.store');
Route::get('/my-bookings/approve/{booking}', [BookingController::class, 'approve'])->name('my-bookings.approve');
Route::get('/my-bookings/cancel/{booking}', [BookingController::class, 'cancel'])->name('my-bookings.cancel');

// Settings page Routes
Route::resource('settings', 'SettingController');
Route::get('/settings', [SettingController::class, 'index'])->name('settings');

// Users page Routes
Route::resource('users', 'UserController');
Route::get('/users', [UserController::class, 'index'])->name('users');

// Reports page Routes
Route::resource('reports', 'ReportController');
Route::get('/reports', [ReportController::class, 'index'])->name('reports');

// Auth pages Routes
Route::group(['middleware'=>'auth'], function(){
    // Profile page Routes
    Route::view('profile', 'profile')->name('profile');
    Route::post('profile', 'ProfileController@update')->name('profile.update');
    
    // Advertisement page Routes
    Route::resource('my-ads', 'AdvertisementController');
    Route::get('/my-ads', [AdvertisementController::class, 'index'])->name('my-ads');
});