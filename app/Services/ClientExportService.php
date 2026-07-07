<?php

namespace App\Services;

use App\Models\Client;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientExportService
{
    public function export(bool $duplicatesOnly = false): StreamedResponse
    {
        $query = Client::query();

        if ($duplicatesOnly) {
            $duplicateFingerprints = Client::select('fingerprint')
                ->groupBy('fingerprint')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('fingerprint');

            $query->whereIn('fingerprint', $duplicateFingerprints);
        }

        $fileName = 'clients_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'company_name',
                'email',
                'phone_number',
            ]);

            $query->orderBy('id')
                ->cursor()
                ->each(function ($client) use ($handle) {

                    fputcsv($handle, [
                        $client->company_name,
                        $client->email,
                        $client->phone_number,
                    ]);

                });

            fclose($handle);

        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}