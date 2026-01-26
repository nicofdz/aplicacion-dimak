<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetCategory;

class AssetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Notebooks',
                'description' => 'Computadores portátiles asignados a personal'
            ],
            [
                'name' => 'Monitores',
                'description' => 'Pantallas externas'
            ],
            [
                'name' => 'Celulares',
                'description' => 'Dispositivos móviles corporativos'
            ],
            [
                'name' => 'Periféricos',
                'description' => 'Teclados, mouse, audífonos, webcams'
            ],
            [
                'name' => 'Muebles de Oficina',
                'description' => 'Sillas, escritorios, estantes'
            ],
            [
                'name' => 'Vehículos',
                'description' => 'Flota vehicular de la empresa'
            ],
            [
                'name' => 'Herramientas',
                'description' => 'Taladros, sierras, herramientas manuales'
            ],
            [
                'name' => 'Electrodomésticos',
                'description' => 'Cafeteras, microondas, refrigeradores'
            ],
        ];

        foreach ($categories as $category) {
            AssetCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
