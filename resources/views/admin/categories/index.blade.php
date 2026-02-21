<x-admin-layout
title="Categorías | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
    ]
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.categories.import') }}" green>
        <i class="fas fa-file-import"></i>
            Importar
        </x-wire-button>
        <x-wire-button href="{{ route('admin.categories.create') }}" blue>
            <i class="fas fa-plus"></i>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.category-table')

    @push('js')
        <script>

            forms = document.querySelectorAll('.delete-form');

            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "¿Estas Seguro?",
                        text: "¡No Podras rever esta Acción",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "¡Sí, Eliminar!",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            })


        </script>
    @endpush


</x-admin-layout>
