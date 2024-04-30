<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\Payment;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/send-code',  [FirebaseController::class, 'sendCode'])->name('code');
// Route::get('/users', [UserController::class, 'index']);

Route::post('/paystack/webhook',  [PaymentController::class, 'handleWebhook'])->name('webhook');