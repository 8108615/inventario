<?php

namespace App\Livewire\Admin;

use App\Models\Inventory;
use App\Models\Movement;
use App\Models\Product;
use Livewire\Component;

class MovementCreate extends Component
{
    public $type = 1;

    public $serie = 'M001';

    public $correlative;

    public $date;

    public $warehouse_id;

    public $reason_id;

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
        $this->correlative = Movement::max('correlative') + 1;
    }

    public function updated($property, $value)
    {
        if ($property == 'type') {
           $this->reset('reason_id');
        }
    }

    public function addProduct()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ],[],[
            'product_id' => 'producto',
            'warehouse_id' => 'Almacén',

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
        $lastRecord = Inventory::where('product_id', $product->id)
            ->where('warehouse_id', $this->warehouse_id)
            ->latest('id')
            ->first();

        $costBalance = $lastRecord?->cost_balance ?? 0;


        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'price' => $costBalance,
            'subtotal' => $costBalance,
        ];

        $this->reset('product_id');
    }

    public function save()
    {
        $this->validate([
            'type' => 'required|in:1,2',
            'serie' => 'required|string|max:10',
            'correlative' => 'required|numeric|min:1',
            'date' => 'nullable|date',
            'warehouse_id' => 'required|exists:warehouses,id',
            'reason_id' => 'required|exists:reasons,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ],[],[
            'type' => 'tipo de movimiento',
            'warehouse_id' => 'Almacén',
            'reason_id' => 'Motivo',
            'observation' => 'observación',
            'products.*.id' => 'producto',
            'products.*.quantity' => 'cantidad',
            'products.*.price' => 'precio',
        ]);

        $movement = Movement::create([
            'type' => $this->type,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'warehouse_id' => $this->warehouse_id,
            'total' => $this->total,
            'observation' => $this->observation,
            'reason_id' => $this->reason_id,
        ]);

        foreach ($this->products as $product) {
            $movement->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['quantity'] * $product['price'],
            ]);

            //Kardex
            $lastRecord = Inventory::where('product_id', $product['id'])
                ->where('warehouse_id', $this->warehouse_id)
                ->latest('id')
                ->first();

            $lastQuantityBalance = $lastRecord?->quantity_balance ?? 0;
            $lastTotalBalance = $lastRecord?->total_balance ?? 0;
            $lastCostBalance = $lastRecord?->cost_balance ?? 0;

            if($this->type == 1){
                $newQuantityBalance = $lastQuantityBalance + $product['quantity'];
                $newTotalBalance = $lastTotalBalance + ($product['quantity'] * $product['price']);
                $newCostBalance =  $newTotalBalance / $newQuantityBalance;
            }elseif($this->type == 2){
                $newQuantityBalance = $lastQuantityBalance - $product['quantity'];
                $newTotalBalance = $lastTotalBalance - ($product['quantity'] * $lastCostBalance);
                $newCostBalance =  $newTotalBalance / max($newQuantityBalance, 1) ;
            }
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'Movimiento Creado Exitosamente',
        ]);

        return redirect()->route('admin.movements.index');
    }

    public function render()
    {
        return view('livewire.admin.movement-create');
    }
}
