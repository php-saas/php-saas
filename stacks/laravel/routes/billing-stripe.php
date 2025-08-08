<?php

use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\Billing\DownloadInvoiceController;
use App\Http\Controllers\Billing\ResumeSubscriptionController;
use App\Http\Controllers\Billing\SubscribeController;
use App\Http\Controllers\Billing\SwapSubscriptionController;
use App\Http\Controllers\Billing\UpdatePaymentMethodController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/billing')->group(function () {
        Route::get('/', [BillingController::class, 'index'])->name('billing.index');
        Route::delete('/', [BillingController::class, 'destroy'])
            ->name('billing.destroy');
        Route::get('/subscribe/{plan}/{billing}', SubscribeController::class)->name('billing.subscribe');
        Route::get('/update-payment-method', UpdatePaymentMethodController::class)
            ->name('billing.update-payment-method');
        Route::post('/resume', ResumeSubscriptionController::class)
            ->name('billing.resume');
        Route::get('/invoices/{id}/download', DownloadInvoiceController::class)
            ->name('billing.invoices.download');
        Route::post('/swap', SwapSubscriptionController::class)
            ->name('billing.swap');
    });
});
