<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Menjalankan seeder kategori.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name'        => 'Pemrograman',
                'description' => 'Buku tentang pemrograman dan pengembangan perangkat lunak',
            ],
            [
                'name'        => 'Desain Grafis',
                'description' => 'Buku tentang desain grafis dan UI/UX',
            ],
            [
                'name'        => 'Basis Data',
                'description' => 'Buku tentang sistem manajemen basis data',
            ],
            [
                'name'        => 'Jaringan Komputer',
                'description' => 'Buku tentang jaringan dan infrastruktur IT',
            ],
            [
                'name'        => 'Sistem Operasi',
                'description' => 'Buku tentang sistem operasi dan administrasi server',
            ],
        ]);
    }
}
