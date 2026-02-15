<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Warehouse;
use Livewire\Component;

class Kardex extends Component
{
    public Product $product;

    public $warehouses;
    public $warehouse_id;

    public $fecha_inicial;
    public $fecha_final;

    public function mount()
    {
        $this->warehouses = Warehouse::all();
        $this->warehouse_id = $this->warehouses->first()->id ?? null;
    }
    public function render()
    {
        return view('livewire.admin.kardex');
    }
}
