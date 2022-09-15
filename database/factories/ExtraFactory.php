<?php

namespace Database\Factories;

use App\Models\Extra;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Extra::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->randomElement([
                '1 espejo roto', 
                '2 silla rota', 
                '3 gaseosas, 1 chocolate consumidos del minibar', 
                '1 botella de vino consumidos del minibar', 
                '1 cama rota',
            ]),
            'price' => $this->faker->numberBetween($min = 150, $max = 500),
        ];
    }
}
