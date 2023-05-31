<?php

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

Route::post('/recovery', [RecoveryController::class, 'recover'])->name('check.recovery');
Route::patch('/recovery/password/{token}', [RecoveryController::class, 'updatePassword'])->name('update.recovery');
