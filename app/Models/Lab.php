<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Lab extends Model
{
    /** @use HasFactory<\Database\Factories\LabFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'has_groups',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'has_groups' => 'boolean',
        ];
    }

    public function groups(): HasMany
    {
        return $this->hasMany(LabGroup::class)->orderBy('nomor');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /** Semua meja milik lab ini (melalui kelompok). */
    public function tables(): HasManyThrough
    {
        return $this->hasManyThrough(LabTable::class, LabGroup::class);
    }
}
