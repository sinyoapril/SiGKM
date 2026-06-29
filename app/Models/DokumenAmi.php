<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenAmi extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_id',
        'nama_dokumen',
        'file_path',
        'link_url',
        'uploaded_by',
    ];

    public function ami(): BelongsTo
    {
        return $this->belongsTo(Ami::class);
    }

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
