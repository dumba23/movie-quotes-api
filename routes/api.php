<?php

use App\Http\Controllers\Auth\GoogleAuthController;
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

Route::get('auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleProviderCallback'])->name('google.callback');

Route::post('/recovery', [RecoveryController::class, 'recover'])->name('check.recovery');
Route::patch('/recovery/password/{token}', [RecoveryController::class, 'updatePassword'])->name('update.recovery');
