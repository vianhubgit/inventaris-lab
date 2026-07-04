<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAudit extends Model
{
    /** @use HasFactory<\Database\Factories\StockAuditFactory> */
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'jumlah_tercatat',
        'jumlah_fisik',
        'selisih',
        'keterangan',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_tercatat' => 'integer',
            'jumlah_fisik' => 'integer',
            'selisih' => 'integer',
            'tanggal' => 'date',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
