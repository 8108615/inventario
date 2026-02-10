<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Quote;
use Livewire\Component;

class QuoteCreate extends Component
{
    public $voucher_type = 1;

    public $serie = 'C001';

    public $correlative;

    public $date;

    public $customer_id;

    public $total = 0;

    public $observation;

    public $product_id;

    public $products = [];

    public function boot()
    {
        //Verificar si hay errores de validacion
        $this->withValidator(function ($validator){
           if($validator->fails()){

                $errors = $validator->errors()->toArray();

                $html = "<ul class='text-left'>";
                foreach($errors as $error){
                    $html.= "<li>{$error[0]}</li>";
                }

                $html .= "</ul>";

                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => '¡Error de Validación!',
                    'html' => $html,
                ]);
           }
        });
    }

    public function mount()
    {
        $this->correlative = Quote::max('correlative') + 1;
    }

    public function addProduct()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
        ],[],[
            'product_id' => 'producto',
        ]);

        $existing = collect($this->products)
            ->firstwhere('id', $this->product_id);

        if ($existing) {

            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Producto ya agregado',
                'text' => 'El producto ya se encuentra en la lista',
            ]);

            return;
        }

        $product = Product::find($this->product_id);

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'price' => $product->price,
            'subtotal' => $product->price,
        ];

        $this->reset('product_id');
    }

    public function save()
    {
        $this->validate([
            'voucher_type' => 'required|in:1,2',
            'date' => 'nullable|date',
            'customer_id' => 'required|exists:customers,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ],[],[
            'voucher_type' => 'tipo de comprobante',
            'customer_id' => 'cliente',
            'observation' => 'observación',
            'products.*.id' => 'producto',
            'products.*.quantity' => 'cantidad',
            'products.*.price' => 'precio',
        ]);

        $quote = Quote::create([
            'voucher_type' => $this->voucher_type,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'customer_id' => $this->customer_id,
            'total' => $this->total,
            'observation' => $this->observation,
        ]);

        foreach ($this->products as $product) {
            $quote->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['quantity'] * $product['price'],
            ]);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'Cotización Creada Exitosamente',
        ]);

        return redirect()->route('admin.quotes.index');
    }

    public function render()
    {
        return view('livewire.admin.quote-create');
    }
}
