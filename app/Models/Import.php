<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    protected $fillable = [
        'original_name',
        'stored_name',
        'status',
        'total_rows',
        'processed_rows',
        'failed_rows',
        'duplicate_rows',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}