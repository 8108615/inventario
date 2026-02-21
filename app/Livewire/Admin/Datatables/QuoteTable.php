<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class QuoteTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Quote::query()
            ->with(['customer']);
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

    public function bulkActions(): array
    {
        return [
            'exportSelected' => 'Exportar',
        ];
    }

    public function exportSelected()
    {
        $selected = $this->getSelected();

        $quotes = count($selected)
            ? Quote::whereIn('id', $selected)
            ->with(['customer.identity'])
            ->get()
            : Quote::with(['customer.identity'])->get();

        return Excel::download(new \App\Exports\QuotesExport($quotes), 'quotes.xlsx');
    }



    //Propiedades
    public $form = [
        'open' => false,
        'document' => '',
        'client' => '',
        'email' => '',
        'model' => null,
        'view_pdf_patch' => 'admin.quotes.pdf',
    ];

     //metodo
    public function openModal(Quote $quote)
    {
        $this->form['open'] = true;
        $this->form['document'] = 'Cotización ' . $quote->serie . '-' . $quote->correlative;
        $this->form['client'] = $quote->customer->document_number . ' -' . $quote->customer->name;
        $this->form['email'] = $quote->customer->email;
        $this->form['model'] = $quote;
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
