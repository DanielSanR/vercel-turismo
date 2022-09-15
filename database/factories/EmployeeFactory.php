<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Entrepreneurship;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

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
            'email' => preg_replace('/@example\..*/', '@gmail.com', $this->faker->unique()->safeEmail),
            'phone' => $this->faker->numerify('3764-######'),
            'image_path' => 'assets/images/default.png',
            'entrepreneurship_id' => $entrepreneurship->id,
        ];
    }
}
