<?php

namespace App\Http\Controllers\Billing;

use Illuminate\Http\RedirectResponse;
use Laravel\Paddle\Transaction;

class DownloadInvoiceController
{
    public function __invoke(Transaction $transaction): RedirectResponse
    {
        if ($transaction->billable_id !== user()->id) {
            abort(404);
        }

        return $transaction->redirectToInvoicePdf();
    }
}
