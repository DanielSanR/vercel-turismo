<?php

namespace Database\Seeders;

use App\Models\Entrepreneurship;
use App\Models\Installation;
use App\Models\LocalService;
use Illuminate\Database\Seeder;

class InstallationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $entrepreneurship = Entrepreneurship::with('localServices')->findOrFail(1);

        $installation = new Installation([
            'category' => 'Cabañas',
            'name' => 'Cabaña para dos',
            'description' => '1 habitacion, living, cocina',
            'capacity' => 2,
            'price' => 600,
            'quantity' => 5,
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);
        $installation->save();


        $installation = new Installation([
            'category' => 'Cabañas',
            'name' => 'Cabaña para cuatro',
            'description' => '2 habitaciones, living, cocina',
            'capacity' => 4,
            'price' => 1000,
            'quantity' => 3,
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);
        $installation->save();


        $installation = new Installation([
            'category' => 'Habitaciones',
            'name' => 'Habitacion individual',
            'description' => 'para una persona',
            'capacity' => 1,
            'price' => 250,
            'quantity' => 3,
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);
        $installation->save();



        $installation = new Installation([
            'category' => 'Habitaciones',
            'name' => 'Habitacion duple',
            'description' => 'para 2 personas',
            'capacity' => 2,
            'price' => 350,
            'quantity' => 3,
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);
        $installation->save();


        $installation = new Installation([
            'category' => 'Habitaciones',
            'name' => 'Habitacion triple',
            'description' => 'para 3 personas',
            'capacity' => 3,
            'price' => 500,
            'quantity' => 4,
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);
        $installation->save();


        $installation = new Installation([
            'category' => 'Habitaciones',
            'name' => 'Habitacion matrimonial',
            'description' => 'cama matrimonial, living pequeño',
            'capacity' => 2,
            'price' => 600,
            'quantity' => 2,
            'entrepreneurship_id' => $entrepreneurship->id,
        ]);
        $installation->save();


        $installations = Installation::all();
        foreach ($installations as $installation) {
            foreach ($entrepreneurship->localServices as $localService) {
                $installation->localServices()->attach($localService);
            } 
        }


        //instalaciones para emprendimientos sin alojamiento
        $installation = new Installation([
            'category' => 'Degustacion',
            'name' => 'Mesa de te',
            'description' => 'mesa para tomar el te',
            'capacity' => 4,
            'price' => 300,
            'quantity' => 10,
            'entrepreneurship_id' => 2,
        ]);
        $installation->save();






    }
}
