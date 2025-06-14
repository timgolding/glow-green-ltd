<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BoilerManufacturer;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BoilerManufacturer>
 */
class BoilerManufacturerFactory extends Factory
{

    protected $model = BoilerManufacturer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company
         ];
       
    }
}
