<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RisikoTemuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'temuan_id',
        'tingkat_risiko_id',
        'deskripsi_risiko',
        'dampak_risiko',
    ];

    public function temuan(): BelongsTo
    {
        return $this->belongsTo(Temuan::class);
    }

    public function tingkatRisiko(): BelongsTo
    {
        return $this->belongsTo(TingkatRisiko::class);
    }
}
