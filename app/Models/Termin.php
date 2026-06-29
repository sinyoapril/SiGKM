<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Termin extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_termin',
        'keterangan',
    ];

    public function jadwalMonevs(): HasMany
    {
        return $this->hasMany(JadwalMonev::class);
    }
}
