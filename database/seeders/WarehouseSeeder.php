<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehouse::create([
            'name' => 'Almacén Principal',
            'location' => 'Av Primaria 123',
        ]);

        Warehouse::create([
            'name' => 'Almacén Secundario',
            'location' => 'calle Secundaria 123',
        ]);
    }
}
