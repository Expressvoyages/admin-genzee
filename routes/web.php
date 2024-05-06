<?php

use App\Models\Gift;
use App\Models\Sticker;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\HelpPageController;
use App\Http\Controllers\Admin\GiftsController;
use App\Http\Controllers\Admin\StickersController;
use App\Http\Controllers\Admin\VerifieldController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\UserController;

Route::get('/', [VerifieldController::class, 'home'])->name('home');
Route::get('/about', [VerifieldController::class, 'about'])->name('about');
Route::get('/pricing', [VerifieldController::class, 'price'])->name('price');
Route::get('/ticket', [HelpPageController::class, 'index'])->name('help.index');
Route::post('/help', [HelpPageController::class, 'store'])->name('help.store');



Route::post('send',[PushNotificationController::class, 'bulksend'])->name('bulksend');
Route::get('all-notifications', [PushNotificationController::class, 'index']);
Route::get('get-notification-form', [PushNotificationController::class, 'create']);



Route::get('/dashboard', [VerifieldController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admininistrators', [VerifieldController::class, 'admin'])->name('admininistrators');
Route::get('/admin-role', [VerifieldController::class, 'role'])->name('roles');
Route::post('/admins', [VerifieldController::class, 'adminstore'])->name('admins.store');
Route::get('/admins/{id}/edit', [VerifieldController::class, 'adminsedit'])->name('admins.edit');
Route::put('/admins/{id}', [VerifieldController::class, 'admiupdate'])->name('admins.update');
Route::delete('/admins/{id}', [VerifieldController::class, 'admindestroy'])->name('admins.destroy');
Route::post('/send-notification', [VerifieldController::class, 'sendNotification'])->name('notify');
Route::get('/push-notification', [VerifieldController::class, 'push'])->name('admins.push');
Route::get('/help', [VerifieldController::class, 'help'])->name('admins.help');
Route::get('/payment', [VerifieldController::class, 'payment'])->name('admins.payment');
Route::get('/delete-account-request', [VerifieldController::class, 'showDeletionForm'])->name('account.delete.form');
Route::get('/fetch-images', [VerifieldController::class, 'fetchImages'])->name('fetch.images');






Route::get('/advert', [FirebaseController::class, 'advert'])->name('users.advert');
Route::post('/advert/update', [FirebaseController::class, 'advertsUpdate'])->name('advert.update');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->group(function () {
    

    // Gifts
    Route::get('/gifts', [GiftsController::class, 'index'])->name('admin.gifts');
    Route::post('/gifts', [GiftsController::class, 'store'])->name('gifts.store');
    Route::delete('/gifts/{gift}', [GiftsController::class, 'destroy'])->name('gifts.destroy');

    // Stickers
    Route::get('/stickers', [StickersController::class, 'index'])->name('admin.stickers');
    Route::post('/stickers', [StickersController::class, 'store'])->name('stickers.store');
    Route::delete('/stickers/{url}', [StickersController::class, 'destroy'])->name('stickers.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/referals', [UserController::class, 'referals'])->name('users.referal');
    
    
    
    Route::get('/Reports', [FirebaseController::class, 'index2'])->name('report.index');

    Route::get('/verifield', [VerifieldController::class, 'index'])->name('users.verify');
    Route::get('/verifield/{id}/edit', [VerifieldController::class, 'edit'])->name('verifield.edit');
    Route::put('/verifield/{id}', [VerifieldController::class, 'update'])->name('verifield.update');
    Route::delete('/verifield/{id}', [VerifieldController::class, 'destroy'])->name('verifield.destroy');

    Route::post('/send-to-firebase',  [FirebaseController::class, 'sendToFirebase'])->name('send-to-firebase');

});



require __DIR__.'/auth.php';

