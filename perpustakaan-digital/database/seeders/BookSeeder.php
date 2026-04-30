<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Menjalankan seeder buku.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'title'  => 'Laravel: Up & Running',
                'author' => 'Matt Stauffer',
                'year'   => 2023,
            ],
            [
                'title'  => 'PHP and MySQL Web Development',
                'author' => 'Luke Welling',
                'year'   => 2022,
            ],
            [
                'title'  => 'Clean Code',
                'author' => 'Robert C. Martin',
                'year'   => 2008,
            ],
            [
                'title'  => 'Design Patterns',
                'author' => 'Gang of Four',
                'year'   => 1994,
            ],
            [
                'title'  => 'The Pragmatic Programmer',
                'author' => 'David Thomas',
                'year'   => 2019,
            ],
        ]);
    }
}
