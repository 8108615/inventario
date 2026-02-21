<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function index()
    {
        return view('admin.movements.index');
    }
    public function create()
    {
        return view('admin.movements.create');
    }

    public function pdf(Movement $movement)
    {
        $pdf = Pdf::loadView('admin.movements.pdf', [
            'model' => $movement,
        ]);
        return $pdf->download("Movimiento_{$movement->id}.pdf");
    }
}
