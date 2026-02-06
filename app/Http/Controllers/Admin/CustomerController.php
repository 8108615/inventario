<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Identity;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $identities = Identity::all();
        return view('admin.customers.create',compact('identities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'identity_id' => 'required|exists:identities,id',
            'document_number' => 'required|string|max:20|unique:customers,document_number',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create($data);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'El cliente ha sido Creado Correctamente.'
        ]);

        return redirect()->route('admin.customers.edit', $customer);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $identities = Identity::all();
        return view('admin.customers.edit', compact('customer','identities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'identity_id' => 'required|exists:identities,id',
            'document_number' => 'required|string|max:20|unique:customers,document_number,' . $customer->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($data);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'El cliente ha sido Actualizado Correctamente.'
        ]);

        return redirect()->route('admin.customers.edit', $customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->quotes()->exists() || $customer->sales()->exists())
        {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'El cliente no puede ser Eliminar porque tiene Cotizaciones o Ventas Asociadas.'
            ]);

            return redirect()->route('admin.customers.index');
        }

        $customer->delete();
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'El cliente ha sido Eliminado Correctamente.'
        ]);

        return redirect()->route('admin.customers.index');
    }
}
