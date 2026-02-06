<x-admin-layout
title="Proveedores | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Proveedores',
        'href' => route('admin.suppliers.index'),
    ],
    [
        'name' => 'Editar',
    ]
]">


    <x.wire-card>
        <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <x-wire-native-select
                    label="Tipo de Documento"
                    name="identity_id">
                    @foreach ($identities as $identity)
                        <option value="{{ $identity->id }}" @selected(old('identity_id', $supplier->identity_id) == $identity->id)>
                            {{ $identity->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input
                    label="Número de documento"
                    name="document_number"
                    placeholder="Número de Documento"
                    value="{{ old('document_number', $supplier->document_number) }}"
                    required />
            </div>

            <x-wire-input
                label="Nombre"
                name="name"
                placeholder="Nombre del Proveedor"
                value="{{ old('name', $supplier->name) }}" />

            <x-wire-input
                label="Dirección"
                name="address"
                placeholder="Dirección del Proveedor"
                value="{{ old('address', $supplier->address) }}" />

            <x-wire-input label="Email"
                name="email"
                type="email"
                placeholder="Correo Electrónico del Proveedor"
                value="{{ old('email', $supplier->email) }}" />

            <x-wire-input label="Teléfono"
                name="phone"
                placeholder="Teléfono del Proveedor"
                value="{{ old('phone', $supplier->phone) }}" />

            <div class="flex justify-end">
                <x-button>
                    Actualizar
                </x-button>
            </div>
        </form>
    </x.wire-card>

</x-admin-layout>
