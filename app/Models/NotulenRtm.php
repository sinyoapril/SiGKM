<?php

namespace App\Models;

use App\Support\WorkflowStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotulenRtm extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_rtm_id',
        'isi_notulen',
        'status',
        'input_by',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function jadwalRtm(): BelongsTo
    {
        return $this->belongsTo(JadwalRtm::class);
    }

    public function penginput(): BelongsTo
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function keputusanRtms(): HasMany
    {
        return $this->hasMany(KeputusanRtm::class);
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, [
            WorkflowStatus::DRAFT,
            WorkflowStatus::DITOLAK,
        ], true);
    }
}
