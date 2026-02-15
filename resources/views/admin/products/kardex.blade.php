<x-admin-layout
title="Productos | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'href' => route('admin.products.index'),
    ],
    [
        'name' => 'Kardex',
    ]
]">

    @livewire('admin.kardex',['product' => $product])

</x-admin-layout>
