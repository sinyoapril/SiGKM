<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalMonev extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_id',
        'termin_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'created_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function termin(): BelongsTo
    {
        return $this->belongsTo(Termin::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ringkasanPerkuliahans(): HasMany
    {
        return $this->hasMany(RingkasanPerkuliahan::class);
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function isOpen(): bool
    {
        return $this->status === 'aktif'
            && now()->between($this->tanggal_mulai->startOfDay(), $this->tanggal_selesai->endOfDay());
    }
}
