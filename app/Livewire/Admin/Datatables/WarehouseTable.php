<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Warehouse;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseTable extends DataTableComponent
{
    protected $model = Warehouse::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Location", "location")
                ->sortable(),
            Column::make('Acciones')
                ->label(function($row){
                    return view('admin.warehouses.actions', ['warehouse' => $row]);
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

        $warehouses = count($selected)
            ? Warehouse::whereIn('id', $selected)->get()
            : Warehouse::all();

        return Excel::download(new \App\Exports\WarehousesExport($warehouses), 'warehouses.xlsx');
    }
}
