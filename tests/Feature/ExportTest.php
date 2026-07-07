<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_endpoint_exists(): void
    {
        $response = $this->get('/api/export');

        $response->assertStatus(200);
    }
}