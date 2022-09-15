<?php

namespace Database\Factories;

use App\Models\LocalService;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocalServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LocalService::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['servicio1', 'servicio2', 'servicio3', 'servicio4', 'servicio5', 'servicio6', 'servicio7']),
            'category' => $this->faker->randomElement(['Comida', 'Deportes', 'Wifi', 'General']),
            'icon' => $this->faker->randomElement(['uno.png', 'dos.png', 'tres.png', 'cuatro.png']),
        ];
    }
}
