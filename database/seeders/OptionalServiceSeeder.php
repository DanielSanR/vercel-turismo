<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entrepreneurship;
use App\Models\OptionalService;

class OptionalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $entrepreneurship = Entrepreneurship::find(1);

        $optionServices = OptionalService::factory(4)->create();        
        
        $entrepreneurship->optionalServices()->saveMany($optionServices);


    }
}
