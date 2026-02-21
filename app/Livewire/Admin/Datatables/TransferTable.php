<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Movement;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Quote;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class TransferTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Transfer::query()
            ->with([
                'originWarehouse',
                'destinationWarehouse',
            ]);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
        $this->setConfigurableAreas([
            'after-wrapper' => [
                'admin.pdf.modal',
            ],
        ]);
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
            Column::make("Almacen de Origen", "originWarehouse.name")
                    ->sortable(),
            Column::make("Almacen de Destino", "destinationWarehouse.name")
                    ->sortable(),
            Column::make("Total", "total")
                    ->sortable()
                    ->format(fn($value) => 'Bs/ ' . number_format($value, 2,'.',',')),
            Column::make("Actiones")
                    ->label(function($row) {
                        return view('admin.transfers.actions', [
                            'transfer' => $row
                        ]);
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

        $transfers = count($selected)
            ? Transfer::whereIn('id', $selected)
            ->with(['originWarehouse', 'destinationWarehouse'])
            ->get()
            : Transfer::with(['originWarehouse', 'destinationWarehouse'])
                ->get();

        return Excel::download(new \App\Exports\TransfersExport($transfers), 'transfers.xlsx');
    }



    //Propiedades
    public $form = [
        'open' => false,
        'document' => '',
        'client' => '',
        'email' => '',
        'model' => null,
        'view_pdf_patch' => 'admin.transfers.pdf',
    ];

     //metodo
    public function openModal(Transfer $tranfer)
    {
        $this->form['open'] = true;
        $this->form['document'] = 'Transferencia ' . $tranfer->serie . '-' . $tranfer->correlative;
        $this->form['client'] = $tranfer->originWarehouse->name;
        $this->form['email'] = '';
        $this->form['model'] = $tranfer;
    }

    public function sendEmail()
    {
        $this->validate([
            'form.email' => 'required|email',
        ]);

        //llamar un mailable
        Mail::to($this->form['email'])
            ->send(new \App\Mail\PdfSend($this->form));

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Correo Enviado',
            'text' => 'El correo ha sido enviado exitosamente',
        ]);

        $this->reset('form');
    }
}
