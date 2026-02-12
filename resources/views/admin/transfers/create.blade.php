<x-admin-layout
title="Trasferencias | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Trasferencias',
        'href' => route('admin.transfers.index'),
    ],
    [
        'name' => 'Nuevo',
    ]

]">

    @livewire('admin.transfer-create')

</x-admin-layout>
