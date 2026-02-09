<x-admin-layout
title="Ordenes de Compra | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Ordenes de Compra',
    ]
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.purchase-orders.create') }}" blue>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.purchase-order-table')

</x-admin-layout>
