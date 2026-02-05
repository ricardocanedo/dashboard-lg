<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar o ID da Plant A
        $plantId = DB::table('plants')->where('name', 'Planta A')->first()->id;

        // Linhas de produção com timestamps realistas
        $productionLines = [
            [
                'name' => 'Geladeira',
                'plant_id' => $plantId,
                'created_at' => now()->subMonths(6),
                'updated_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Máquina de Lavar',
                'plant_id' => $plantId,
                'created_at' => now()->subMonths(5),
                'updated_at' => now()->subMonths(5),
            ],
            [
                'name' => 'TV',
                'plant_id' => $plantId,
                'created_at' => now()->subMonths(4),
                'updated_at' => now()->subMonths(4),
            ],
            [
                'name' => 'Ar-Condicionado',
                'plant_id' => $plantId,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(3),
            ],
        ];

        DB::table('production_lines')->insert($productionLines);
    }
}
