<?php

namespace App\Http\Controllers\Billing;

use Symfony\Component\HttpFoundation\Response;

class DownloadInvoiceController
{
    public function __invoke(string $id): Response
    {
        return user()->downloadInvoice($id);
    }
}
