<?php

namespace App\Http\Controllers;

use App\Services\ClientExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function export(
        Request $request,
        ClientExportService $service
    ) {
        return $service->export(
            $request->boolean('duplicates')
        );
    }
}