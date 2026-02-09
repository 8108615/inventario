<x-admin-layout
title="Compras | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Compras',
        'href' => route('admin.purchases.index'),
    ],
    [
        'name' => 'Nuevo',
    ]

]">

    @livewire('admin.purchase-create')

</x-admin-layout>
