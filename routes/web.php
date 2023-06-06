<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

Route::prefix('auth/google')->group(function () {
	Route::get('/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
	Route::get('/callback', [GoogleAuthController::class, 'handleProviderCallback'])->name('google.callback');
});
