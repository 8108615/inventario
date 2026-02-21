<div>
    <x-wire-card>
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">
            Importar Productos desde Excel
        </h1>

        <x-wire-button blue wire:click="downloadTemplate">
            <i class="fas fa-file-excel"></i>
            Descargar Plantilla
        </x-wire-button>

        <p class="text-sm text-gray-500 mt-1">
            Complete la platilla con los datos de tus Productos y subelos aquí.
        </p>

        <div class="mt-4">
            <input type="file" accept=".xlsx, .xls" wire:model="file" />

            <x-input-error for="file" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-wire-button
                green
                wire:click="importProducts"
                wire:loading.attr="disabled"
                wire:target="file"
                spinner="importProducts"
                >
                <i class="fas fa-upload mr-2"></i>
                Importar Categorías
            </x-wire-button>

        </div>
        @if(count($errors))
            <div class="mt-4">

                <div class="p-4 bg-yellow-100 border border-yellow-300 rounded-md text-yellow-800 mb-3">

                    @if ($importedCount)
                        <i class="fas fa-triangle-exclamation mr-2"></i>
                        <strong>Importacion Completada Parcialmente</strong>

                        <p class="mt-1 text-sm">
                            Algunos Productos no se pudieron Importar debido a Errores.
                        </p>
                    @else
                        <i class="fas fa-xmark mr-2"></i>
                        <strong>No se Importo Ningun Producto</strong>

                        <p class="mt-1 text-sm">
                            Todos los Productos tienen errores o el archivo es fallido.
                        </p>
                    @endif
                </div>

                <ul class="space-y-2">
                    @foreach ($errors as $error)
                        <li class="p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-red-700 font-semibold">
                                <i class="fas fa-file-pen"></i>
                                Fila {{ $error['row'] }}:
                            </p>

                            <ul class="list-disc list-inside mt-1">
                                @foreach ($error['errors'] as $message)
                                    <li class="text-red-600 text-sm">
                                        {{ $message }}
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </x-wire-card>
</div>
