<?php

namespace App\Models;

use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'category_id',
        'lab_id',
        'lab_table_id',
        'jumlah_total',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'status' => ItemStatus::class,
            'jumlah_total' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function labTable(): BelongsTo
    {
        return $this->belongsTo(LabTable::class, 'lab_table_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(StockAudit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('nama', 'like', "%{$term}%")
                ->orWhere('keterangan', 'like', "%{$term}%");
        });
    }

    /** Lokasi ringkas untuk ditampilkan. */
    public function getLokasiLengkapAttribute(): string
    {
        $lab = $this->lab?->nama ?? '-';

        if ($this->labTable) {
            $group = $this->labTable->group;

            return trim("{$lab} • {$group?->display_name} • {$this->labTable->display_name}", ' •');
        }

        return $lab;
    }
}
