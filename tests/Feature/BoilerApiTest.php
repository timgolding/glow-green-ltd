<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Boiler;

class BoilerApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Set up test user with token
        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'sanctum');
    }

    public function test_can_create_boiler()
    {
        $manufacturer = \App\Models\BoilerManufacturer::factory()->create();
        $fuel = \App\Models\BoilerFuelType::factory()->create();

        $payload = [
            'name' => 'Test Boiler',
            'manufacturer_part_number' => 'ABC123',
            'boiler_manufacturer_id' => $manufacturer->id,
            'fuel_type_id' => $fuel->id,
        ];

        $response = $this->postJson('/api/boilers', $payload);
        $response->assertStatus(201)
             ->assertJsonFragment(['name' => 'Test Boiler']);
    }

    public function test_validation_errors_on_post()
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/boilers', []);

        $response->assertStatus(422)
             ->assertJson([
                 'error' => 'Validation failed.',
             ])
             ->assertJsonStructure([
                 'error',
                 'details' => ['name', 'manufacturer_part_number', 'boiler_manufacturer_id', 'fuel_type_id'],
             ]);
    }

    public function test_can_soft_delete_boiler()
    {
        $boiler = Boiler::factory()->create();

        $response = $this->deleteJson("/api/boilers/{$boiler->id}");
        $response->assertStatus(200);
        $this->assertSoftDeleted($boiler);
    }

    public function test_can_restore_boiler()
    {
        $boiler = Boiler::factory()->create();
        $boiler->delete();

        $response = $this->postJson("/api/boilers/{$boiler->id}/restore");
        $response->assertStatus(200);
        $this->assertDatabaseHas('boilers', ['id' => $boiler->id, 'deleted_at' => null]);
    }

}
