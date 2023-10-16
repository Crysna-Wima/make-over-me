<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run()
    {
        // [1,"Kecamatan Wonocolo","35.78.02",654.4464729262487,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2020"],
        // [2,"Kecamatan Rungkut","35.78.03",2294.278258383593,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2020"],
        // [3,"Kecamatan Wonokromo","35.78.04",827.6812081260327,"ada","Pemkot Surabaya","Pemkot Surabaya","Februari 2021"],
        // [4,"Kecamatan Tegalsari","35.78.05",433.33749284669886,"ada","Pemkot Surabaya","Pemkot Surabaya","April 2020"],
        // [6,"Kecamatan Genteng","35.78.07",409.193344862331,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2020"],
        // [7,"Kecamatan Gubeng","35.78.08",792.9092367374957,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2020"],
        // [8,"Kecamatan Sukolilo","35.78.09",3017.792536079271,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2021"],
        // [9,"Kecamatan Tambaksari","35.78.10",896.5725892876136,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2020"],
        // [10,"Kecamatan Simokerto","35.78.11",262.99644486694353,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2020"],
        // [12,"Kecamatan Bubutan","35.78.13",390.5992141362917,"ada","Pemkot Surabaya","Pemkot Surabaya","Juni 2020"],
        // [18,"Kecamatan Benowo","35.78.19",2963.870924099401,"ada","Pemkot Surabaya","Pemkot Surabaya","November 2020"],
        // [21,"Kecamatan Gayungan","35.78.22",591.2133121593065,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2020"],
        // [22,"Kecamatan Jambangan","35.78.23",411.911069171905,"ada","Pemkot Surabaya","Pemkot Surabaya","Desember 2020"],
        // [24,"Kecamatan Tenggilis Mejoyo","35.78.24",580.853525960313,"ada","Pemkot Surabaya","Pemkot Surabaya","Agustus 2021"],
        // [25,"Kecamatan Gunung Anyar","35.78.25",1015.2698576705931,"ada","Pemkot Surabaya","Pemkot Surabaya","Agustus 2021"],
        // [30,"Kecamatan Pakal","35.78.30",1857.556744458374,"ada","Pemkot Surabaya","Pemkot Surabaya","Oktober 2021"]
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

        $this->command->info('Berhasil menambahkan data kecamatan');
    }
}
