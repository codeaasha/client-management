<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->boolean('duplicates')) {
            $duplicateFingerprints = Client::select('fingerprint')
                ->groupBy('fingerprint')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('fingerprint');

            $query->whereIn('fingerprint', $duplicateFingerprints);
        }

        return response()->json(
            $query->latest()->paginate(20)
        );
    }
}