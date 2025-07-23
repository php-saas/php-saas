<?php

use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\Billing\DownloadInvoiceController;
use App\Http\Controllers\Billing\ResumeSubscriptionController;
use App\Http\Controllers\Billing\SwapSubscriptionController;
use App\Http\Controllers\Billing\UpdatePaymentMethodController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Laravel\Paddle\Http\Controllers\WebhookController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // <php-saas:billing>
    Route::get('billing', [BillingController::class, 'index'])
        ->name('billing.index');
    Route::delete('billing', [BillingController::class, 'destroy'])
        ->name('billing.destroy');
    Route::get('billing/update-payment-method', UpdatePaymentMethodController::class)
        ->name('billing.update-payment-method');
    Route::post('billing/resume', ResumeSubscriptionController::class)
        ->name('billing.resume');
    Route::get('billing/transactions/{transaction}/download', DownloadInvoiceController::class)
        ->name('billing.invoices.download');
    Route::post('billing/swap', SwapSubscriptionController::class)
        ->name('billing.swap');
    // </php-saas:billing>
});

// <php-saas:billing>
Route::post('paddle/webhook', [WebhookController::class, '__invoke']);
// </php-saas:billing>

require __DIR__.'/auth.php';

require __DIR__.'/settings.php';
