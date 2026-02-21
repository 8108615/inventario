<x-admin-layout
title="Productos | Inventario"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
    ]
]">

    @push('css')
        <style>
            table th span, table td {
                font-size: 0.75rem !important;
            }

            .image-product {
                width: 5rem !important;
                height: 3rem !important;
                object-fit: cover !important;
                object-position: center !important;

            }
        </style>
    @endpush

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.products.import') }}" green>
        <i class="fas fa-file-import"></i>
            Importar
        </x-wire-button>

        <x-wire-button href="{{ route('admin.products.create') }}" blue>
            <i class="fas fa-plus"></i>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.product-table')

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
