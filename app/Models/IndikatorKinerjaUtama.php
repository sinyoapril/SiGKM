<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndikatorKinerjaUtama extends Model
{
    use HasFactory;

    protected $fillable = ['sasaran_strategis_id', 'kode_iku', 'uraian_iku', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function sasaranStrategis(): BelongsTo
    {
        return $this->belongsTo(SasaranStrategis::class);
    }

    public function indikatorKinerjaKegiatans(): HasMany
    {
        return $this->hasMany(IndikatorKinerjaKegiatan::class);
    }
}
