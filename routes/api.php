<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;

Route::post('/imports', [ImportController::class, 'store']);
Route::get('/imports', [ImportController::class, 'index']);
Route::get('/imports/{import}', [ImportController::class, 'show']);

Route::get('/clients', [ClientController::class, 'index']);

Route::get('/export', [ExportController::class, 'export']);