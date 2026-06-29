<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JadwalRtm extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'semester_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'agenda',
        'created_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function notulenRtm(): HasOne
    {
        return $this->hasOne(NotulenRtm::class);
    }

    public function scopeMendatang(Builder $query): Builder
    {
        return $query->whereDate('tanggal', '>=', today())
            ->orderBy('tanggal');
    }
}
