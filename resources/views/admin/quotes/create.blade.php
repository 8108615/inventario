<x-admin-layout
title="Ordenes de Compra | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Ordenes de Compra',
        'href' => route('admin.purchase-orders.index'),
    ],
    [
        'name' => 'Nuevo',
    ]

]">

    @livewire('admin.purchase-order-create')

</x-admin-layout>
