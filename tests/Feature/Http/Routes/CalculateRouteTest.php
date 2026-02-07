<?php

namespace Tests\Feature\Http\Routes;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CalculateRouteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_should_calculate_budget_via_api(): void
    {
        $payload = [
            'id' => 'teste',
            'product' => [
                ['id' => 1, 'quantity' => 2]
            ],
            'user_id' => 1
        ];

        $response = $this->postJson('/api/calculate', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'total',
                'performance' => ['duration_ms']
            ])
            ->assertJson([
                'id' => 'teste',
            ]);
    }

    public function test_should_cache_calculate_budget_via_api(): void
    {
        $payload = [
            'id' => 'teste',
            'product' => [
                ['id' => 1, 'quantity' => 2]
            ],
            'user_id' => 1
        ];

        $response = $this->postJson('/api/calculate', $payload);

        $this->assertIsFloat($response['total']);

        $this->assertTrue(Cache::has('teste'));
    }
}
