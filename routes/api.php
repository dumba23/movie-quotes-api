<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerifyController;
use App\Http\Controllers\Auth\PasswordRecoveryController;

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

Route::middleware('localization')->group(function () {
	Route::get('/language/{locale}', function ($locale) {
		App::setLocale($locale);

		return response()->json(['Language changed', app()->getLocale()]);
	});

	Route::controller(AuthController::class)->group(function () {
		Route::post('/register', 'register')->name('register');
		Route::post('/login', 'login')->name('login');
	});

	Route::get('/register/verify/{token}', [EmailVerifyController::class, 'verify'])->name('verify.register');

	Route::controller(PasswordRecoveryController::class)->group(function () {
		Route::post('/recovery', 'recover')->name('check.recovery');
		Route::get('/recovery/validate', 'handleTokenExpiration')->name('validate.recovery');
		Route::patch('/recovery/password/{token}', 'updatePassword')->name('update.recovery');
	});

	Route::middleware('auth:sanctum')->group(function () {
		Route::get('/auth/check', function () {
			return response()->json(['authenticated' => true, App::getLocale()]);
		});

		Route::post('logout', [AuthController::class, 'logout'])->name('logout');

		Route::controller(UserController::class)->group(function () {
			Route::get('/users', 'get')->name('get.users');
			Route::post('/users/edit', 'update')->name('update.users');
			Route::patch('/users/change/{old_email}/{decryptedEmail}', 'updateEmail')->name('update.users_email');
		});

		Route::controller(MovieController::class)->group(function () {
			Route::get('/movies', 'index')->name('index.movies');
			Route::get('/movies/authorized', 'authorizedUserMovies')->name('authorized.movies');
			Route::get('/movies/{movie}', 'get')->name('get.movies');
			Route::post('/movies', 'store')->name('store.movies');
			Route::put('/movies/{movie}', 'update')->name('update.movies');
			Route::delete('/movies/{movie}', 'destroy')->name('destroy.movies');
		});

		Route::controller(QuoteController::class)->group(function () {
			Route::get('/quotes', 'index')->name('index.quotes');
			Route::get('/quotes/authorized', 'authorizedUserQuotes')->name('authorized.quotes');
			Route::get('/quotes/{quote}', 'get')->name('get.quotes');
			Route::post('/quotes', 'store')->name('store.quotes');
			Route::put('/quotes/{quote}', 'update')->name('update.quotes');
			Route::post('/quotes/{quote}/like', 'like')->name('like.quote');
			Route::delete('/quotes/{quote}', 'destroy')->name('destroy.quotes');
		});

		Route::post('/quotes/{quote}/comments', [CommentController::class, 'store'])->name('store.quote_comment');

		Route::controller(NotificationController::class)->group(function () {
			Route::get('/notifications', 'index')->name('index.notifications');
			Route::post('/notifications', 'store')->name('store.notifications');
			Route::patch('/notifications/mark-all-as-read', 'markAllAsRead')->name('mark_all_as_read');
			Route::put('/notifications/mark-selected-as-read', 'markSelectedAsRead')->name('mark_selected_as_read');
		});

		Route::get('/genres', [GenreController::class, 'index'])->name('genres');
	});
});
