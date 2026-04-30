<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();                     // Kolom id (BIGINT UNSIGNED, PK, AUTO_INCREMENT)
            $table->string('title');          // Kolom title VARCHAR(255) NOT NULL
            $table->string('author');         // Kolom author VARCHAR(255) NOT NULL
            $table->unsignedSmallInteger('year')->nullable();  // Kolom year SMALLINT, boleh NULL
            $table->timestamps();             // Kolom created_at & updated_at
        });
    }

    /**
     * Membalikkan migration (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
