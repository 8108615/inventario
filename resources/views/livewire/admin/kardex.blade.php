<div>

    <x-wire-card>

        <div class="grid grid-cols-2 gap-4">
            <x-wire-input label="Fecha Inicial" type="date" wire:model="fecha_inicial" />
            <x-wire-input label="Fecha Final" type="date" wire:model="fecha_final" />

            <x-wire-select label="Almacen"
                wire:model="warehouse_id"
                :options="$warehouses->select('id', 'name')"
                option-label="name"
                option-value="id"
                />

        </div>
    </x-wire-card>
</div>
