<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_endpoint_exists(): void
    {
        $response = $this->getJson('/api/clients');

        $response->assertStatus(200);
    }

    public function test_duplicate_filter_endpoint_exists(): void
    {
        $response = $this->getJson('/api/clients?duplicates=true');

        $response->assertStatus(200);
    }
}