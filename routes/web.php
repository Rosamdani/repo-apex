<?php

use App\Http\Controllers\ResetPasswordController;
use App\Http\Middleware\AnotherDeviceMiddleware;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\CheckTryoutDeadline;
use App\Http\Middleware\CheckTryoutPermission;
use App\Models\Tryouts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthMiddleware::class])->get('/', [App\Http\Controllers\TryoutController::class, 'index'])->name('index');
Route::middleware([AuthMiddleware::class])->get('/katalog', [App\Http\Controllers\TryoutController::class, 'katalog'])->name('katalog');
Route::middleware([AuthMiddleware::class])->get('/katalog/detail/{id}', [App\Http\Controllers\TryoutController::class, 'katalaogDetail'])->name('katalog.detail');
Route::middleware([AuthMiddleware::class])->get('/katalog/paketan/{id}', [App\Http\Controllers\TryoutController::class, 'paketanDetail'])->name('katalog.paketan.detail');

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::prefix('/tryout')->group(function () {
        Route::middleware([CheckTryoutPermission::class, CheckTryoutDeadline::class])->get('/show/{id}', [App\Http\Controllers\TryoutController::class, 'show'])->name('tryouts.show');
        Route::middleware([CheckTryoutPermission::class])->get('/hasil/{id}', [App\Http\Controllers\TryoutHasilController::class, 'index'])->name('tryouts.hasil.index');
        Route::post('/downloadReportBidang', [App\Http\Controllers\PDFController::class, 'downloadReportBidang'])->name('tryout.downloadReportBidang');
        Route::middleware([CheckTryoutPermission::class])->get('/pembahasan/{id}', [App\Http\Controllers\TryoutHasilController::class, 'pembahasan'])->name('tryouts.hasil.pembahasan');
        Route::middleware([CheckTryoutPermission::class])->get('/pembahasan-perbidang/{id}/{categoryId}', [App\Http\Controllers\TryoutHasilController::class, 'pembahasanByCategory'])->name('tryouts.hasil.pembahasanByCategory');
        Route::get('/ranking/{id}', [App\Http\Controllers\TryoutHasilController::class, 'ranking'])->name('tryouts.hasil.perangkingan');
    });
});
// Route::middleware([AuthMiddleware::class])->get(uri: '/tryout/perangkingan/{id}', [App\Http\Controllers\TryoutHasilController::class, 'perangkingan'])->name('tryouts.hasil.perangkinan');
Route::middleware([AuthMiddleware::class])->post('/testimoni/store', [App\Http\Controllers\TestimoniController::class, 'store'])->name('testimoni.store');

Route::middleware([AuthMiddleware::class])->get('/profile/my-profile', function () {
    return view('profile.view-profile');
})->name('my-profile');

Route::get('/infophp', function () {
    phpinfo();
});


Route::middleware('guest')->get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::middleware('auth')->post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::middleware('guest')->post('/logoutSession', [App\Http\Controllers\AuthController::class, 'logoutSession'])->name('logoutSession');
Route::middleware('guest')->post('/login', [App\Http\Controllers\AuthController::class, 'loginStore'])->name('loginStore');
Route::middleware('guest')->get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::middleware('guest')->post('/register', [App\Http\Controllers\AuthController::class, 'registerStore'])->name('registerStore');
Route::middleware(['guest', 'anotherdevice'])->get('/another-device', [App\Http\Controllers\AuthController::class, 'showSessions'])->name('anotherDevice');
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');
Route::post('/forgot-password', [ResetPasswordController::class, 'password'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'passwordReset'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'passwordUpdate'])->middleware('guest')->name('password.update');


Route::get('/private-image/{path}', function ($path) {
    $filePath = storage_path('app/private/' . $path);

    if (!file_exists($filePath)) {
        abort(404);
    }

    return response()->file($filePath);
})->where('path', '.*')->name('private.image');
