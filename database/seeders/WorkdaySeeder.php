<?php

namespace Database\Seeders;

use App\Models\Entrepreneurship;
use App\Models\Workday;
use Illuminate\Database\Seeder;

class WorkdaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $entrepreneurship = Entrepreneurship::findOrFail(1);

        Workday::create([
            'day' => 'lunes',
            'opening' => '08:00:00',
            'closing' => '23:59:00',
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);

        Workday::create([
            'day' => 'martes',
            'opening' => '08:00:00',
            'closing' => '23:59:00',
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);

        Workday::create([
            'day' => 'miercoles',
            'opening' => '08:00:00',
            'closing' => '23:59:00',
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);

        Workday::create([
            'day' => 'jueves',
            'opening' => '08:00:00',
            'closing' => '23:59:00',
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);

        Workday::create([
            'day' => 'viernes',
            'opening' => '08:00:00',
            'closing' => '23:59:00',
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);

        Workday::create([
            'day' => 'sabado',
            'opening' => '10:00:00',
            'closing' => '21:00:00',
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);
        
        Workday::create([
            'day' => 'domingo',
            'opening' => '10:00:00',
            'closing' => '21:00:00',
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);
        
    }
}
