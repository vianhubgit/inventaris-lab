<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTable extends Model
{
    /** @use HasFactory<\Database\Factories\LabTableFactory> */
    use HasFactory;

    protected $fillable = [
        'lab_group_id',
        'nomor',
        'nama',
    ];

    protected function casts(): array
    {
        return [
            'nomor' => 'integer',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(LabGroup::class, 'lab_group_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nama ?: "Meja {$this->nomor}";
    }
}
