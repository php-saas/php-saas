<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Laravel\Paddle\Http\Controllers\WebhookController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('billing/update-payment-method', [BillingController::class, 'updatePaymentMethod'])->name('billing.update-payment-method');
    Route::delete('billing/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');
    Route::post('billing/resume', [BillingController::class, 'resume'])->name('billing.resume');
    Route::get('billing/transactions/{transaction}/download', [BillingController::class, 'download'])->name('billing.invoices.download');
});

Route::post('paddle/webhook', [WebhookController::class, '__invoke']);

require __DIR__ . '/settings.php';
