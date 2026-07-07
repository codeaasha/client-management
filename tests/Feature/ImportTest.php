<?php

namespace Tests\Feature;

use App\Jobs\ProcessCsvImportJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_csv_file_is_required(): void
    {
        $response = $this->postJson('/api/imports');

        $response->assertStatus(422);
    }

    public function test_invalid_file_type_is_rejected(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->postJson('/api/imports', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
    }

    public function test_valid_csv_dispatches_job(): void
    {
        Queue::fake();

        $file = UploadedFile::fake()->createWithContent(
            'clients.csv',
            "company_name,email,phone_number\nABC,abc@gmail.com,9801000000"
        );

        $response = $this->postJson('/api/imports', [
            'file' => $file,
        ]);

        $response->assertStatus(202);

        Queue::assertPushed(ProcessCsvImportJob::class);
    }
}