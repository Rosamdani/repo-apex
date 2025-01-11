<?php

use App\Http\Middleware\AnotherDeviceMiddleware;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\CheckTryoutPermission;
use App\Models\Tryouts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthMiddleware::class])->get('/', [App\Http\Controllers\TryoutController::class, 'index'])->name('index');
Route::middleware([AuthMiddleware::class])->get('/katalog', [App\Http\Controllers\TryoutController::class, 'katalog'])->name('katalog');
Route::middleware([AuthMiddleware::class])->get('/katalog/detail/{id}', [App\Http\Controllers\TryoutController::class, 'katalaogDetail'])->name('katalog.detail');

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::prefix('/tryout')->group(function () {
        Route::middleware([CheckTryoutPermission::class])->get('/show/{id}', [App\Http\Controllers\TryoutController::class, 'show'])->name('tryouts.show');
        Route::middleware([CheckTryoutPermission::class])->get('/hasil/{id}', [App\Http\Controllers\TryoutHasilController::class, 'index'])->name('tryouts.hasil.index');
        Route::post('/downloadReportBidang', [App\Http\Controllers\PDFController::class, 'downloadReportBidang'])->name('tryout.downloadReportBidang');
        Route::middleware([CheckTryoutPermission::class])->get('/pembahasan/{id}', [App\Http\Controllers\TryoutHasilController::class, 'pembahasan'])->name('tryouts.hasil.pembahasan');
        Route::get('/ranking/{id}', [App\Http\Controllers\TryoutHasilController::class, 'ranking'])->name('tryouts.hasil.perangkingan');
    });
});
// Route::middleware([AuthMiddleware::class])->get(uri: '/tryout/perangkingan/{id}', [App\Http\Controllers\TryoutHasilController::class, 'perangkingan'])->name('tryouts.hasil.perangkinan');
Route::middleware([AuthMiddleware::class])->post('/testimoni/store', [App\Http\Controllers\TestimoniController::class, 'store'])->name('testimoni.store');

Route::middleware([AuthMiddleware::class])->get('/profile/my-profile', function () {
    return view('profile.view-profile');
})->name('my-profile');

Route::middleware('guest')->get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::middleware('auth')->post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::middleware('guest')->post('/logoutSession', [App\Http\Controllers\AuthController::class, 'logoutSession'])->name('logoutSession');
Route::middleware('guest')->post('/login', [App\Http\Controllers\AuthController::class, 'loginStore'])->name('loginStore');
Route::middleware('guest')->get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::middleware('guest')->post('/register', [App\Http\Controllers\AuthController::class, 'registerStore'])->name('registerStore');
Route::middleware(['guest', 'anotherdevice'])->get('/another-device', [App\Http\Controllers\AuthController::class, 'showSessions'])->name('anotherDevice');
