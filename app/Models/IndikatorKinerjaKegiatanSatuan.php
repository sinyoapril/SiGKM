<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class IndikatorKinerjaKegiatanSatuan extends Model
{
    use HasFactory;

    protected $fillable = ['indikator_kinerja_kegiatan_id', 'kode_ikks', 'uraian_ikks', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function indikatorKinerjaKegiatan(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerjaKegiatan::class);
    }

    public function evaluasiIndikators(): MorphMany
    {
        return $this->morphMany(EvaluasiIndikator::class, 'evaluatable');
    }
}
