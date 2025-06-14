<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Boiler;
use App\Models\BoilerManufacturer;
use App\Models\BoilerFuelType;
use Illuminate\Support\Str;

class BoilerDataSeeder extends Seeder
{
    public function run()
    {
        $apiKey = env('GLOW_GREEN_API_KEY');
        $apiUrl = env('GLOW_GREEN_API_URL');

        $response = Http::withHeaders([
            'X-GG-API-Key' => $apiKey,
        ])->get($apiUrl);

        if ($response->failed()) {
            $this->command->error('Failed to fetch boiler data');
            return;
        }

        $boilers = $response->json('data');

        foreach ($boilers as $boilerData) {
            // Create or find Manufacturer
            $manufacturerData = $boilerData['boiler_manufacturer'];
            $manufacturer = BoilerManufacturer::firstOrCreate(
                ['id' => $manufacturerData['id']],
                ['name' => $manufacturerData['name']]
            );

            // Create or find FuelType
            $fuelData = $boilerData['fuel_type'];
            $fuelType = BoilerFuelType::firstOrCreate(
                ['id' => $fuelData['id']],
                ['name' => $fuelData['name'], 'fuel_type_ref' => $fuelData['fuel_type_ref']]
            );

            // Create or update Boiler
            $boiler = Boiler::updateOrCreate([
   		   'id' => $boilerData['id'],
       	     ], [
                'name' => $boilerData['name'],
                'sku' => $boilerData['sku'],
                'description' => $boilerData['description'],
                'url' => str::slug($boilerData['name']),
                'manufacturer_part_number' => $boilerData['manufacturer_part_number'],
                'boiler_manufacturer_id' => $manufacturer->id,
                'fuel_type_id' => $fuelType->id,
            ]);


        }

        $this->command->info('Boilers seeded successfully.');
    }
}
