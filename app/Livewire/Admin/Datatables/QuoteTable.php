<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class QuoteTable extends DataTableComponent
{
    protected $model = Quote::class;

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
                        return view('admin.quotes.actions', [
                            'quote' => $row
                        ]);
                    })

        ];
    }

    public function builder(): Builder
    {
        return Quote::query()
            ->with(['customer']);
    }
}
