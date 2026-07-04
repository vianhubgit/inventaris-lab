<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabGroup extends Model
{
    /** @use HasFactory<\Database\Factories\LabGroupFactory> */
    use HasFactory;

    protected $fillable = [
        'lab_id',
        'nomor',
        'nama',
    ];

    protected function casts(): array
    {
        return [
            'nomor' => 'integer',
        ];
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(LabTable::class)->orderBy('nomor');
    }

    /** Nama tampil, mis. "Kelompok 1". */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama ?: "Kelompok {$this->nomor}";
    }
}
