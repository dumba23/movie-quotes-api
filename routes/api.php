<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\RecoveryController;

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

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/register/verify/{token}', [VerifyController::class, 'verify'])->name('verify.register');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::prefix('/recovery')->group(function () {
	Route::post('/', [RecoveryController::class, 'recover'])->name('check.recovery');
	Route::get('/validate', [RecoveryController::class, 'handleTokenExpiration'])->name('validate.recovery');
	Route::patch('/password/{token}', [RecoveryController::class, 'updatePassword'])->name('update.recovery');
});

Route::middleware('auth:sanctum')->group(function () {
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');

	Route::get('/user', [ProfileController::class, 'getUser'])->name('user');
	Route::post('/user/edit', [ProfileController::class, 'editUser'])->name('update.user');
	Route::patch('user/change/{old_email}/{decryptedEmail}', [ProfileController::class, 'changeEmail'])->name('change.user_email');

	Route::resource('movies', MovieController::class);
    Route::resource('quotes', QuoteController::class);

	Route::get('/genres', [GenreController::class, 'getGenres'])->name('genres');
});
