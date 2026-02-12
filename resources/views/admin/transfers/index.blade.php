<x-admin-layout
title="Trasferencias | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Trasferencias',
    ]
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.transfers.create') }}" blue>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.transfer-table')

</x-admin-layout>
