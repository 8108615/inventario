<?php

namespace App\Livewire\Admin;

use App\Facades\Kardex;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Sale;
use App\Services\KardexService;
use Livewire\Component;

class SaleCreate extends Component
{
    public $voucher_type = 1;

    public $serie = "F001";

    public $correlative;

    public $date;

    public $quote_id;

    public $customer_id;

    public $warehouse_id;

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

    public function updated($property, $value)
    {
        if($property == 'quote_id'){
            $quote = Quote::find($value);

            if($quote)
                {
                    $this->voucher_type = $quote->voucher_type;

                    $this->customer_id = $quote->customer_id;

                    $this->products = $quote->products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'quantity' => $product->pivot->quantity,
                            'price' => $product->pivot->price,
                            'subtotal' => $product->pivot->quantity * $product->pivot->price,
                        ];
                    })->toArray();
                }
        }

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
            'serie' => 'required|string|max:10',
            'correlative' => 'required|numeric|min:1',
            'date' => 'nullable|date',
            'quote_id' => 'nullable|exists:quotes,id',
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
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

        $sale = Sale::create([
            'voucher_type' => $this->voucher_type,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'quote_id' => $this->quote_id,
            'customer_id' => $this->customer_id,
            'warehouse_id' => $this->warehouse_id,
            'total' => $this->total,
            'observation' => $this->observation,
        ]);

        foreach ($this->products as $product) {
            $sale->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['quantity'] * $product['price'],
            ]);



            Kardex::registerExit($sale, $product, $this->warehouse_id, 'Venta');
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'La Venta se ha Creado Correctamente',
        ]);

        return redirect()->route('admin.sales.index');
    }

    public function render()
    {
        return view('livewire.admin.sale-create');
    }
}
