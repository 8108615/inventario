<x-wire-modal-card wire:model="form.open" width="lg">
    <p class="text-xl text-center mb-2">
        Enviar Email
    </p>

    <p class="text-lg text-center uppercase mb-2">
       {{--   Documento F001 - 123 --}}
       {{ $form['document'] }}
    </p>

    <p class="text-center uppercase mb-2">
        {{--  20609278235 - Inventario Erick --}}
        {{ $form['client'] }}
    </p>

    <form wire:submit="sendEmail">
        <x-wire-input
            label="Correo Elctronico"
            wire:model="form.email"
            class="mb-4"
        />

        <x-wire-button class="w-full" type="submit">
            Enviar correo
        </x-wire-button>
    </form>
</x-wire-modal-card>
