<?php

namespace App\Jobs;

use App\Models\Import;
use App\Services\ClientImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCsvImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $path,
        public int $importId
    ) {
    }

    public function handle(ClientImportService $service): void
    {
        $import = Import::findOrFail($this->importId);

        $service->import(
            storage_path('app/private/' . $this->path),
            $import
        );
    }
}