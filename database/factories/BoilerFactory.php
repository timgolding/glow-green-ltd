<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Boiler;
use App\Models\BoilerManufacturer;
use App\Models\BoilerFuelType;

class BoilerFactory extends Factory
{
    protected $model = Boiler::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Boiler',
            'manufacturer_part_number' => $this->faker->bothify('###??'),
            'boiler_manufacturer_id' => BoilerManufacturer::factory(),
            'fuel_type_id' => BoilerFuelType::factory(),
            'description' => $this->faker->paragraph,
            'sku' => $this->faker->unique()->numerify('SKU####'),
            'url' => $this->faker->slug
        ];
    }
}
