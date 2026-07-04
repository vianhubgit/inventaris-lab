<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function procurements(): HasMany
    {
        return $this->hasMany(Procurement::class);
    }
}
