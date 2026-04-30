<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    /**
     * Kolom yang dapat diisi massal (mass assignment).
     */
    protected $fillable = [
        'title',
        'author',
        'year',
        'category_id',
    ];

    /**
     * Relasi: Buku milik satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
