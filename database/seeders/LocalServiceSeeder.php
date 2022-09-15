<?php

namespace Database\Seeders;

use App\Models\Entrepreneurship;
use App\Models\LocalService;
use Illuminate\Database\Seeder;

class LocalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $entrepreneurship = Entrepreneurship::find(1);

        $localService = LocalService::factory(6)->create();        
        
        $entrepreneurship->localServices()->saveMany($localService);

    }
}
