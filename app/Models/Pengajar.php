<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengajar extends Model
{
    use HasFactory;

    protected $fillable = [
        'perkuliahan_id',
        'dosen_id',
    ];

    public function perkuliahan(): BelongsTo
    {
        return $this->belongsTo(Perkuliahan::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
