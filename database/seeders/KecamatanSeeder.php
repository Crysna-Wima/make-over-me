<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_kecamatan' => 'Wonocolo',
                'kode_kecamatan' => '35.78.02',
                'luas_wilayah_kecamatan' => 654.4464729262487,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2020',
            ],
            [
                'nama_kecamatan' => 'Rungkut',
                'kode_kecamatan' => '35.78.03',
                'luas_wilayah_kecamatan' => 2294.278258383593,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2020',
            ],
            [
                'nama_kecamatan' => 'Wonokromo',
                'kode_kecamatan' => '35.78.04',
                'luas_wilayah_kecamatan' => 827.6812081260327,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Februari 2021',
            ],
            [
                'nama_kecamatan' => 'Tegalsari',
                'kode_kecamatan' => '35.78.05',
                'luas_wilayah_kecamatan' => 433.33749284669886,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'April 2020',
            ],
            [
                'nama_kecamatan' => 'Genteng',
                'kode_kecamatan' => '35.78.07',
                'luas_wilayah_kecamatan' => 409.193344862331,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2020',
            ],
            [
                'nama_kecamatan' => 'Gubeng',
                'kode_kecamatan' => '35.78.08',
                'luas_wilayah_kecamatan' => 792.9092367374957,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2020',
            ],
            [
                'nama_kecamatan' => 'Sukolilo',
                'kode_kecamatan' => '35.78.09',
                'luas_wilayah_kecamatan' => 3017.792536079271,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2021',
            ],
            [
                'nama_kecamatan' => 'Tambaksari',
                'kode_kecamatan' => '35.78.10',
                'luas_wilayah_kecamatan' => 896.5725892876136,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2020',
            ],
            [
                'nama_kecamatan' => 'Simokerto',
                'kode_kecamatan' => '35.78.11',
                'luas_wilayah_kecamatan' => 262.99644486694353,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2020',
            ],
            [
                'nama_kecamatan' => 'Bubutan',
                'kode_kecamatan' => '35.78.13',
                'luas_wilayah_kecamatan' => 390.5992141362917,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Juni 2020',
            ],
            [
                'nama_kecamatan' => 'Benowo',
                'kode_kecamatan' => '35.78.19',
                'luas_wilayah_kecamatan' => 2963.870924099401,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkot Surabaya',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkab Sidoarjo',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'November 2020',
            ],
            [
                'nama_kecamatan' => 'Gayungan',
                'kode_kecamatan' => '35.78.22',
                'luas_wilayah_kecamatan' => 591.2133121593065,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => 'Pemkab Sidoarjo',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => 'Pemkab Sidoarjo',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2020',
            ],
            [
                'nama_kecamatan' => 'Jambangan',
                'kode_kecamatan' => '35.78.23',
                'luas_wilayah_kecamatan' => 411.911069171905,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => '',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => '',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Desember 2020',
            ],
            [
                'nama_kecamatan' => 'Tenggilis Mejoyo',
                'kode_kecamatan' => '35.78.24',
                'luas_wilayah_kecamatan' => 580.853525960313,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => '',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => '',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Agustus 2021',
            ],
            [
                'nama_kecamatan' => 'Gunung Anyar',
                'kode_kecamatan' => '35.78.25',
                'luas_wilayah_kecamatan' => 1015.2698576705931,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => '',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => '',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Agustus 2021',
            ],
            [
                'nama_kecamatan' => 'Pakal',
                'kode_kecamatan' => '35.78.30',
                'luas_wilayah_kecamatan' => 1857.556744458374,
                'keberadaan_kantor_administrasi_pemerintahan' => 'ada',
                'status_kepemilikan_tanah_kantor_administrasi_pemerintahan' => '',
                'status_kepemilikan_bangunan_kantor_administrasi_pemerintahan' => '',
                'tanggal_terakhir_dilakukan_pendataan_batas_wilayah' => 'Oktober 2021',
            ],
        ];

        DB::table('kecamatan')->insert($data);

        $this->command->info('Berhasil menambahkan data kecamatan');
    }
}
