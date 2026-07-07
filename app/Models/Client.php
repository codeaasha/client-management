<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    protected $fillable = [
        'import_id',
        'company_name',
        'email',
        'phone_number',
        'fingerprint',
    ];

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}