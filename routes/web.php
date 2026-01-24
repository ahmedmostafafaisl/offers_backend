<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Dashboard\LandingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::redirect('/dash', '/dashboard');

Route::middleware(['auth', 'dashboard.access'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        Route::get('/', fn() => view('dashboard.home'))->name('home');

        require __DIR__ . '/dashboard.php';
    });


Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ar'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    return back();
})->name('lang.switch');

Route::get('/', [LandingController::class, 'index'])->name('landing');



// Geidea Payment routes
Route::get('/geidea/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/geidea/payment-failed', [PaymentController::class, 'failed'])->name('payment.failed');

// Moyasar Payment routes
Route::get('/moyasar/payment-success', [PaymentController::class, 'success'])->name('moyasar.payment.success');
Route::get('/moyasar/payment-failed', [PaymentController::class, 'failed'])->name('moyasar.payment.failed');
