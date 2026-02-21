<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('admin.purchases.index');
    }
    public function create()
    {
        return view('admin.purchases.create');
    }

    public function pdf(Purchase $purchase)
    {
        $pdf = Pdf::loadView('admin.purchases.pdf', [
            'model' => $purchase,
        ]);
        return $pdf->download("compra_{$purchase->id}.pdf");
    }
}
