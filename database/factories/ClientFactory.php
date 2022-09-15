<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Entrepreneurship;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $entrepreneurship = Entrepreneurship::findOrFail(1);

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'dni' => $this->faker->numerify('########'),
            'date_birth' => $this->faker->dateTimeBetween('1960-01-01', '2015-12-31')->format('Y/m/d'),
            'reason' => 'turismo',
            'departure_locality' => $this->faker->randomElement(['Posadas', 'Iguazu']),
            'residence_locality' => 'Posadas',
            'entrepreneurship_id' => $entrepreneurship->id,
        ];
    }
}
