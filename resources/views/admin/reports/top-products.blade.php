<x-admin-layout
title="Reportes | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos más Vendidos',
    ]
]">

    @livewire('Admin.datatables.top-products-table')
</x-admin-layout>
