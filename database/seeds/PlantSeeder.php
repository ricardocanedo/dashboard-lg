<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plants')->insert([
            ['name' => 'Planta A', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
