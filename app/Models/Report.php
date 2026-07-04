<?php

namespace App\Models;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'lab_id',
        'lab_group_id',
        'lab_table_id',
        'item_id',
        'jumlah',
        'keterangan',
        'foto',
        'status',
        'reported_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => ReportType::class,
            'status' => ReportStatus::class,
            'jumlah' => 'integer',
            'reported_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(LabGroup::class, 'lab_group_id');
    }

    public function labTable(): BelongsTo
    {
        return $this->belongsTo(LabTable::class, 'lab_table_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeType(Builder $query, ReportType|string $type): Builder
    {
        $type = $type instanceof ReportType ? $type->value : $type;

        return $query->where('type', $type);
    }

    public function scopeRusak(Builder $query): Builder
    {
        return $query->where('type', ReportType::RUSAK);
    }

    public function scopeHilang(Builder $query): Builder
    {
        return $query->where('type', ReportType::HILANG);
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/'.$this->foto) : null;
    }
}
