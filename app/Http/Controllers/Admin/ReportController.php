<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function topProducts()
    {
        // Lógica para obtener los productos más vendidos
        return view('admin.reports.top-products');
    }

    public function topCustomers()
    {
        // Lógica para obtener los clientes que más han comprado
        return view('admin.reports.top-customers');
    }

    public function lowStock()
    {
        // Lógica para obtener los productos con bajo stock
        return view('admin.reports.low-stock');
    }
}
