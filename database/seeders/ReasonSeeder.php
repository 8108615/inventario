<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            //Razones para ingresos
            [
                'name' => 'Ajuste por inventario',
                'type' => 1
            ],
            [
                'name' => 'Devolución de Cliente',
                'type' => 1
            ],
            [
                'name' => 'Producción terminada',
                'type' => 1
            ],
            [
                'name' => 'Error en la salida anterior',
                'type' => 1
            ],

            //Razones para salidas
            [
                'name' => 'Ajuste por inventario',
                'type' => 2],
            [
                'name' => 'Merma o deterioro',
                'type' => 2],
            [
                'name' => 'Consumo interno',
                'type' => 2],
            [
                'name' => 'Caducidad',
                'type' => 2
            ],

        ];

        foreach ($reasons as $reason) {
            \App\Models\Reason::create($reason);
        }


    }
}
