<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BoilerFuelType;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BoilerFuelType>
 */
class BoilerFuelTypeFactory extends Factory
{

    protected $model = BoilerFuelType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->title,
            'fuel_type_ref' => $this->faker->title,
        ];
    }
}
