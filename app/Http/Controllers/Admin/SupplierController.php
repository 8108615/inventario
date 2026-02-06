<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Identity;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $identities = Identity::all();
        return view('admin.suppliers.create',compact('identities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'identity_id' => 'required|exists:identities,id',
            'document_number' => 'required|string|max:20|unique:suppliers,document_number',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $supplier = Supplier::create($data);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'El Proveedor ha sido Creado Correctamente.'
        ]);

        return redirect()->route('admin.suppliers.edit', $supplier);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $identities = Identity::all();
        return view('admin.suppliers.edit', compact('supplier','identities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'identity_id' => 'required|exists:identities,id',
            'document_number' => 'required|string|max:20|unique:suppliers,document_number,' . $supplier->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $supplier->update($data);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'El cliente ha sido Actualizado Correctamente.'
        ]);

        return redirect()->route('admin.suppliers.edit', $supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchaseOrders()->exists() || $supplier->purchases()->exists())
        {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'El Proveedor no puede ser Eliminar porque tiene órdenes de Compras o Ventas Asociadas.'
            ]);

            return redirect()->route('admin.suppliers.index');
        }

        $supplier->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'El Proveedor ha sido Eliminado Correctamente.'
        ]);

        return redirect()->route('admin.suppliers.index');
    }
}
