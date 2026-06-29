<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiTindakLanjut extends Model
{
    use HasFactory;

    protected $fillable = [
        'rencana_tindak_lanjut_id',
        'file_path',
        'keterangan',
        'uploaded_by',
    ];

    public function rencanaTindakLanjut(): BelongsTo
    {
        return $this->belongsTo(RencanaTindakLanjut::class);
    }

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
