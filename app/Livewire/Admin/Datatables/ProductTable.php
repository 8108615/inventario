<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Inventory;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;

class ProductTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Product::query()->with(['category', 'images']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');

        $this->setConfigurableAreas([
            'after-wrapper' => [
                'admin.products.modal',
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),

            ImageColumn::make('Imagen')
                 ->location(
                    fn($row) => $row->image
                 )->attributes(
                    fn($row) => [
                        /*'class' => 'w-20 h-10 object-cover object-center',*/
                        'class' => 'image-product',
                    ]
                 ),
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable(),

            Column::make("Categoría", "category.name")
                    ->searchable()
                    ->sortable(),
            Column::make("Precio", "price")
                ->sortable(),
            Column::make("Stock", "stock")
                ->sortable()
                ->format(function($value, $row){
                    return view('admin.products.stock',[
                        'stock' => $value,
                        'product' => $row
                    ]);
                }),
             Column::make('Acciones')
                ->label(function($row){
                    return view('admin.products.actions', ['product' => $row]);
                })
        ];
    }

    public function bulkActions(): array
    {
        return [
            'exportSelected' => 'Exportar',
        ];
    }

    public function exportSelected()
    {
        $selected = $this->getSelected();

        $products = count($selected)
            ? Product::whereIn('id', $selected)->get()
            : Product::all();

        return Excel::download(new \App\Exports\ProductsExport($products), 'products.xlsx');
    }



    //Propiedades

    public $openModal = false;

    public $inventories = [];

    //Metodos
    public function showStock($productId)
    {
        $this->openModal = true;

        $latestInventory = Inventory::where('product_id',$productId)
            ->select('warehouse_id', DB::raw('MAX(id) as id'))
            ->groupBy('warehouse_id')
            ->pluck('id');

        $this->inventories = Inventory::whereIn('id', $latestInventory)
            ->with(['warehouse'])
            ->get();
    }
}
