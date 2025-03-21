<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateCarrerasSeeder extends Seeder
{
    public function run()
    {
        $carreras = [];
        
        for ($i = 1; $i <= 20; $i++) {
            $carreras[] = [
                'name' => 'Carrera ' . $i,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        DB::table('races')->insert($carreras);
    }
}
