<?php

use App\Http\Middleware\AuthMiddleware;
use App\Models\Tryouts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $tryout = Tryouts::first();
    return redirect()->route('tryout.index');
});

Route::middleware([AuthMiddleware::class])->prefix('/tryout')->group(function () {
    Route::get('/', [App\Http\Controllers\TryoutController::class, 'index'])->name('tryout.index');
    Route::post('/tryout-get', [App\Http\Controllers\TryoutController::class, 'getTryouts'])->name('tryouts.getTryouts');
    Route::post('/getUserTryoutData', [App\Http\Controllers\TryoutController::class, 'getUserTryoutData'])->name('tryout.getUserTryoutData');
    Route::post('/getNotStartedTryouts', [App\Http\Controllers\TryoutController::class, 'getNotStartedTryouts'])->name('tryout.getNotStartedTryouts');
    Route::post('/getLeaderboard', [App\Http\Controllers\TryoutController::class, 'getLeaderboard'])->name('tryout.getLeaderboard');
    Route::get('/show/{id}', [App\Http\Controllers\TryoutController::class, 'show'])->name('tryouts.show');
    Route::get('/pembahasan/{id}', [App\Http\Controllers\TryoutController::class, 'pembahasan'])->name('tryouts.pembahasan');
    Route::get('/pembahasan/{id}', [App\Http\Controllers\TryoutController::class, 'pembahasan'])->name('tryouts.pembahasan');
    Route::get('/my-tryout', [App\Http\Controllers\TryoutController::class, 'mytryout'])->name('tryouts.mytryout');
});
Route::middleware([AuthMiddleware::class])->post('/tryout/getQuestions', [App\Http\Controllers\TryoutController::class, 'getQuestions'])->name('tryout.getQuestions');
Route::middleware([AuthMiddleware::class])->post('/tryout/getPembahasan', [App\Http\Controllers\TryoutController::class, 'getPembahasan'])->name('tryout.getPembahasan');
Route::middleware([AuthMiddleware::class])->post('/tryout/saveAnswer', [App\Http\Controllers\TryoutController::class, 'saveAnswer'])->name('tryout.saveAnswer');
Route::middleware([AuthMiddleware::class])->post('/tryout/saveEndTime', [App\Http\Controllers\TryoutController::class, 'saveEndTime'])->name('tryout.saveEndTime');
Route::middleware([AuthMiddleware::class])->post('/tryout/getTimeLeft', [App\Http\Controllers\TryoutController::class, 'getTimeLeft'])->name('tryout.getTimeLeft');
Route::middleware([AuthMiddleware::class])->post('/tryout/saveTimeLeft', [App\Http\Controllers\TryoutController::class, 'saveTimeLeft'])->name('tryout.saveTimeLeft');
Route::middleware([AuthMiddleware::class])->post('/tryout/finish', [App\Http\Controllers\TryoutController::class, 'finishTryout'])->name('tryout.finish');
Route::middleware([AuthMiddleware::class])->post('/tryout/pause', [App\Http\Controllers\TryoutController::class, 'pausedTime'])->name('tryout.pause');
Route::middleware([AuthMiddleware::class])->post('/tryout/getResult', [App\Http\Controllers\TryoutHasilController::class, 'getKompetensiAnswer'])->name('tryout.getResult');
Route::middleware([AuthMiddleware::class])->post('/tryout/downloadReportBidang', [App\Http\Controllers\PDFController::class, 'downloadReportBidang'])->name('tryout.downloadReportBidang');
Route::middleware([AuthMiddleware::class])->get('/tryout/hasil/{id}', [App\Http\Controllers\TryoutHasilController::class, 'index'])->name('tryouts.hasil.index');
Route::middleware([AuthMiddleware::class])->get('/tryout/perangkingan/{id}', [App\Http\Controllers\TryoutHasilController::class, 'perangkingan'])->name('tryouts.hasil.perangkinan');
Route::middleware([AuthMiddleware::class])->get('/tryout/pembahasan/{id}', [App\Http\Controllers\TryoutHasilController::class, 'pembahasan'])->name('tryouts.hasil.pembahasan');
Route::middleware([AuthMiddleware::class])->post('/tryout/getChartSubTopik', [App\Http\Controllers\TryoutHasilController::class, 'persentaseBidang'])->name('tryouts.hasil.getChartSubTopik');
Route::middleware([AuthMiddleware::class])->post('/tryout/myTryout', [App\Http\Controllers\TryoutHasilController::class, 'persentaseBidang'])->name('tryouts.hasil.myTryout');
Route::middleware([AuthMiddleware::class])->post('/testimoni/store', [App\Http\Controllers\TestimoniController::class, 'store'])->name('testimoni.store');

Route::middleware('guest')->get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::middleware('auth')->post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::middleware('guest')->post('/login', [App\Http\Controllers\AuthController::class, 'loginStore'])->name('loginStore');
Route::middleware('guest')->get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::middleware('guest')->post('/register', [App\Http\Controllers\AuthController::class, 'registerStore'])->name('registerStore');
