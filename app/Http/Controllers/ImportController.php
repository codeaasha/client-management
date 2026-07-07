<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCsvRequest;
use App\Jobs\ProcessCsvImportJob;
use App\Models\Import;

class ImportController extends Controller
{
    public function index()
    {
        return response()->json(
            Import::latest()->paginate(10)
        );
    }

    public function show(Import $import)
    {
        return response()->json($import);
    }

    public function store(ImportCsvRequest $request)
    {
        $path = $request->file('file')->store('imports');

        $import = Import::create([
            'original_name' => $request->file('file')->getClientOriginalName(),
            'stored_name' => $path,
            'status' => 'pending',
        ]);

        ProcessCsvImportJob::dispatch($path, $import->id);

        return response()->json([
            'message' => 'Import started successfully.',
            'import_id' => $import->id,
        ], 202);
    }
}