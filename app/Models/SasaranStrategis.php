<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SasaranStrategis extends Model
{
    use HasFactory;

    protected $table = 'sasaran_strategis';

    protected $fillable = ['kode_sasaran', 'uraian_sasaran', 'is_active', 'created_by'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function indikatorKinerjaUtamas(): HasMany
    {
        return $this->hasMany(IndikatorKinerjaUtama::class);
    }
}
