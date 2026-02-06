<x-admin-layout
title="Almacenes | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Almacenes',
        'href' => route('admin.warehouses.index'),
    ],
    [
        'name' => 'Nuevo',
    ]
]">


    <x.wire-card>
        <form action="{{ route('admin.warehouses.store') }}" method="POST" class="space-y-4">
            @csrf

            <x-wire-input
                name="name"
                label="Nombre"
                placeholder="Nombre del Almacén"
                value="{{ old('name') }}" />

            <x-wire-input
                name="location"
                label="Ubicación"
                placeholder="Ubicación del Almacén"
                value="{{ old('location') }}" />

            <div class="flex justify-end">
                <x-button>
                    Guardar
                </x-button>
            </div>
        </form>
    </x.wire-card>

</x-admin-layout>
