<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriLayanan = [
            [
                'nama' => 'Make Up',
            ],
            [
                'nama' => 'Hair Do',
            ],
            [
                'nama' => 'Nail Art',
            ],
            [
                'nama' => 'Prewedding',
            ],
            [
                'nama' => 'Wedding',
            ],
            [
                'nama' => 'Engagement',
            ],
            [
                'nama' => 'Graduation',
            ],
            [
                'nama' => 'Party',
            ],
            [
                'nama' => 'Photoshoot',
            ],
            [
                'nama' => 'Event',
            ],
            [
                'nama' => 'Daily',
            ],
            [
                'nama' => 'Other',
            ],
        ];

        foreach ($kategoriLayanan as $kategori) {
            \App\Models\KategoriLayanan::create($kategori);
        }

        $this->command->info('Berhasil menambahkan data kategori layanan');
    }
}
