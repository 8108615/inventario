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
        'name' => 'Editar',
    ]
]">


    <x.wire-card>
        <form action="{{ route('admin.warehouses.update', $warehouse) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <x-wire-input label="Nombre" name="name" placeholder="Nombre del Almacén" value="{{ old('name', $warehouse->name) }}" />

            <x-wire-input
                name="location"
                label="Ubicación"
                placeholder="Ubicación del Almacén"
                value="{{ old('location', $warehouse->location) }}" />

            <div class="flex justify-end">
                <x-button>
                    Actualizar
                </x-button>
            </div>
        </form>
    </x.wire-card>

</x-admin-layout>
