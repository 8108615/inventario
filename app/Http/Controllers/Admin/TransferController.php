<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        return view('admin.transfers.index');
    }
    public function create()
    {
        return view('admin.transfers.create');
    }

    public function pdf(Transfer $transfer)
    {
        $pdf = Pdf::loadView('admin.transfers.pdf', [
            'model' => $transfer,
        ]);
        return $pdf->download("Transferencia_{$transfer->id}.pdf");
    }
}
