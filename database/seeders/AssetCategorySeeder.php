<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetCategory;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nombre' => 'Equipos de Oficina', 'descripcion' => 'Computadores, impresoras, etc.'],
            ['nombre' => 'Herramientas', 'descripcion' => 'Herramientas manuales y eléctricas'],
            ['nombre' => 'Mobiliario', 'descripcion' => 'Mesas, sillas, escritorios'],
            ['nombre' => 'Equipos de Construcción', 'descripcion' => 'Maquinaria y equipos pesados'],
            ['nombre' => 'Vehículos Menores', 'descripcion' => 'Motos, bicicletas, etc.'],
            ['nombre' => 'Tecnología', 'descripcion' => 'Laptops, tablets, teléfonos'],
        ];

        foreach ($categories as $category) {
            AssetCategory::firstOrCreate(
                ['nombre' => $category['nombre']],
                $category
            );
        }
    }
}
