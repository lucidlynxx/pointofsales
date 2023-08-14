<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function printPdf()
    {
        $sales = Sale::all()->sortByDesc('updated_at');

        $pdf = Pdf::loadview('print.pdf', ['sales' => $sales]);
        return $pdf->stream();
    }
}
