<?php

namespace Database\Seeders;

use App\Models\Entrepreneurship;
use Illuminate\Database\Seeder;

class EntrepreneurshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //EMPRENDIMIENTO CON ALOJAMIENTO
        $coords = [-27.01680,-54.52408];
        $entrepreneurship = new Entrepreneurship([
            'name' => 'Las Cabañas',
            'email' => 'lascabañas@gmail.com',
            'address' => 'Picada Santa Rosa',
            'phone' => '3755123456',
            'locality' => 'San Vicente',
            'department' => 'Guaraní',
            'coordinates' => json_encode($coords),
            'accommodation' => 'yes',   
        ]);
        $entrepreneurship->save();

        
        //EMPRENDIMIENTO SIN ALOJAMIENTO
        $coords = [-27.05296,-55.24606];
        $entrepreneurship = new Entrepreneurship([
            'name' => 'El Jardín',
            'email' => 'eljardin@gmail.com',
            'address' => 'Ruta 12, Jardín America',
            'phone' => '3743654321',
            'locality' => 'Jardín America',
            'department' => 'San Ignacio',
            'coordinates' => json_encode($coords),
            'accommodation' => 'no',   
        ]);
        $entrepreneurship->save();


        //EMPRENDIMIENTO CON ALOJAMIENTO - VACIO
        $coords = [-27.48772,55.87300];
        $entrepreneurship = new Entrepreneurship([
            'name' => 'Estancia Posadas',
            'email' => 'eposadas@gmail.com',
            'address' => 'Ruta 12, km 206',
            'phone' => '3764123456',
            'locality' => 'Garupá',
            'department' => 'Capital',
            'coordinates' => json_encode($coords),
            'accommodation' => 'yes',   
        ]);
        $entrepreneurship->save();


        //EMPRENDIMIENTO SIN ALOJAMIENTO - VACIO
        $coords = [-27.41897,-55.99357];
        $entrepreneurship = new Entrepreneurship([
            'name' => 'Granja Posadas',
            'email' => 'gposadas"gmail.com',
            'address' => 'Ruta 12, Itaembe guazu',
            'phone' => '3764321654',
            'locality' => 'Posadas',
            'department' => 'Capital',
            'coordinates' => json_encode($coords),
            'accommodation' => 'no',   
        ]);
        $entrepreneurship->save();

    }
}
