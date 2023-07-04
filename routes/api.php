<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
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

	Route::get('all/quotes', [QuoteController::class, 'allQuotes'])->name('all_quotes');
	Route::resource('quotes', QuoteController::class);
	Route::post('quotes/{quote}/like', [QuoteController::class, 'like'])->name('like_quote');

	Route::post('quotes/{quote}/comments', [CommentController::class, 'store'])->name('store.quote_comment');

	Route::get('notification', [NotificationController::class, 'getNotifications'])->name('get_notification');
	Route::post('notification', [NotificationController::class, 'store'])->name('store.notification');

	Route::patch('notifications/{notificationId}', [NotificationController::class, 'markAsRead'])->name('mark_read');
	Route::patch('notification/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark_all_read');

	Route::get('/genres', [GenreController::class, 'getGenres'])->name('genres');
});
