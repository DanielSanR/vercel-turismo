<?php

namespace Database\Factories;

use App\Models\OptionalService;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionalServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OptionalService::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['servicioOpcional1', 'servicioOpcional2', 'servicioOpcional3', 'servicioOpcional4']),
            'description' => $this->faker->randomElement(['Viaje a lugar turistico', 'Cabalgata', 'Degustacion productos locales', 'Recorrer plantaciones de té']),
            'price' => $this->faker->numberBetween($min = 50, $max = 250),
            'icon' => $this->faker->randomElement(['uno.png', 'dos.png', 'tres.png', 'cuatro.png']),
        ];
    }
}
