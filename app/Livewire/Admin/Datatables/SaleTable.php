<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Purchase;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class SaleTable extends DataTableComponent
{
    protected $model = PurchaseOrder::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
        /*$this->setAdditionalSelects(['purchase_orders.id']);*/
    }

    public function filters(): array
    {
        return [
            DateRangeFilter::make('Fecha')
                ->config([
                    'placeholder' => 'Selecciona un rango de fechas',
                ])
                ->filter(function($query, array $datesRange) {
                    $query->whereBetween('date', [
                        $datesRange['minDate'],
                        $datesRange['maxDate']
                    ]);
                })
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Date", "date")
                    ->sortable()
                    ->format(fn($value) => $value->format('Y-m-d')),
            Column::make("Serie", "serie")
                    ->sortable(),
            Column::make("Correlativo", "correlative")
                    ->sortable(),
            Column::make("Document", "customer.document_number")
                    ->sortable(),
            Column::make("Razon Social", "customer.name")
                    ->sortable(),
            Column::make("Total", "total")
                    ->sortable()
                    ->format(fn($value) => 'Bs/ ' . number_format($value, 2,'.',',')),
            Column::make("Actiones")
                    ->label(function($row) {
                        return view('admin.sales.actions', [
                            'sale' => $row
                        ]);
                    })

        ];
    }

    public function builder(): Builder
    {
        return Sale::query()
            ->with(['customer']);
    }
}
