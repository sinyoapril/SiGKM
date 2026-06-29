<?php

namespace App\Models;

use App\Support\WorkflowStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RencanaTindakLanjut extends Model
{
    use HasFactory;

    protected $fillable = [
        'temuan_id',
        'uraian_rencana_tindak_lanjut',
        'uraian_tindak_koreksi',
        'target_selesai',
        'status',
        'submitted_at',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'target_selesai' => 'date',
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function temuan(): BelongsTo
    {
        return $this->belongsTo(Temuan::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function buktiTindakLanjuts(): HasMany
    {
        return $this->hasMany(BuktiTindakLanjut::class);
    }

    public function keputusanRtms(): HasMany
    {
        return $this->hasMany(KeputusanRtm::class);
    }

    public function scopeBelumSelesai(Builder $query): Builder
    {
        return $query->whereNotIn('status', [WorkflowStatus::DIVERIFIKASI]);
    }

    public function isOverdue(): bool
    {
        return $this->target_selesai !== null
            && $this->target_selesai->isPast()
            && $this->status !== WorkflowStatus::DIVERIFIKASI;
    }

    public function hasEvidence(): bool
    {
        return $this->buktiTindakLanjuts()->exists();
    }

    public function canBeEditedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->temuan?->belongsToDosen($user)
            && in_array($this->status, [WorkflowStatus::DRAFT, WorkflowStatus::DITOLAK], true);
    }

    public function isDiajukan(): bool
    {
        return $this->status === WorkflowStatus::DIAJUKAN;
    }

    public function isDiverifikasi(): bool
    {
        return $this->status === WorkflowStatus::DIVERIFIKASI;
    }

    public function canBeVerifiedBy(?User $user): bool
    {
        return $user
            && $user->hasRole('ketua-gkm')
            && $this->isDiajukan();
    }
}
