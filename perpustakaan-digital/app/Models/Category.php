<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Kolom yang dapat diisi massal (mass assignment).
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi: Kategori memiliki banyak buku.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
