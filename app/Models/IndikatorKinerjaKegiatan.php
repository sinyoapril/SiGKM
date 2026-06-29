<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IndikatorKinerjaKegiatan extends Model
{
    use HasFactory;

    protected $fillable = ['indikator_kinerja_utama_id', 'kode_ikk', 'uraian_ikk', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function indikatorKinerjaUtama(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerjaUtama::class);
    }

    public function indikatorKinerjaKegiatanSatuan(): HasOne
    {
        return $this->hasOne(IndikatorKinerjaKegiatanSatuan::class);
    }
}
