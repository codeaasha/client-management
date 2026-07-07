<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Import;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\LazyCollection;

class ClientImportService
{
    protected const CHUNK_SIZE = 1000;

    public function import(string $filePath, Import $import): void
    {
        $import->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);

        $batch = [];
        $processed = 0;
        $failed = 0;
        $duplicates = 0;

        LazyCollection::make(function () use ($filePath) {
            $file = fopen($filePath, 'r');

            // Skip header
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                yield $row;
            }

            fclose($file);
        })
        ->chunk(self::CHUNK_SIZE)
        ->each(function ($rows) use (
            &$batch,
            &$processed,
            &$failed,
            &$duplicates,
            $import
        ) {

            $batch = [];

            foreach ($rows as $row) {

                $data = [
                    'company_name' => trim($row[0] ?? ''),
                    'email' => trim($row[1] ?? ''),
                    'phone_number' => trim($row[2] ?? ''),
                ];

                $validator = Validator::make($data, [
                    'company_name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email'],
                    'phone_number' => ['required', 'string', 'max:30'],
                ]);

                if ($validator->fails()) {
                    $failed++;
                    continue;
                }

                $fingerprint = $this->generateFingerprint(
                    $data['company_name'],
                    $data['email'],
                    $data['phone_number']
                );

                $exists = Client::where('fingerprint', $fingerprint)->exists();

                if ($exists) {
                    $duplicates++;
                }

                $batch[] = [
                    'import_id' => $import->id,
                    'company_name' => $data['company_name'],
                    'email' => strtolower($data['email']),
                    'phone_number' => preg_replace('/\D/', '', $data['phone_number']),
                    'fingerprint' => $fingerprint,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $processed++;
            }

            if (! empty($batch)) {
                DB::transaction(function () use ($batch) {
                    Client::insert($batch);
                });
            }

            $import->update([
                'processed_rows' => $processed,
                'failed_rows' => $failed,
                'duplicate_rows' => $duplicates,
            ]);
        });

        $import->update([
            'status' => 'completed',
            'completed_at' => now(),
            'total_rows' => $processed + $failed,
        ]);
    }

    private function generateFingerprint(
        string $company,
        string $email,
        string $phone
    ): string {
        return hash(
            'sha256',
            strtolower(trim($company))
            . '|'
            . strtolower(trim($email))
            . '|'
            . preg_replace('/\D/', '', $phone)
        );
    }
}