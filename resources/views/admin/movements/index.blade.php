<x-admin-layout
title="Entradas y Salidas | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Entradas y Salidas ',
    ]
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.movements.create') }}" blue>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.movement-table')

</x-admin-layout>
