<?php

use Illuminate\Support\Facades\Route;
use App\Models\Sales;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sales/{sale}/receipt', function (Sales $sale) {
    $sale->load('user');
    $pdf = Pdf::loadView('pdf.sales-receipt', ['sale' => $sale]);
    $pdf->setPaper([0, 0, 226.77, 600], 'portrait'); // 80mm width
    return $pdf->stream('struk-' . $sale->invoice_number . '.pdf');
})->middleware('auth')->name('sales.receipt');
