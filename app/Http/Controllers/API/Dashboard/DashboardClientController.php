<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\PencariJasaMua;
use App\Models\PenyediaJasaMua;
use App\Models\Pemesanan;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardClientController extends Controller
{
    public function index($limit = null)
    {
        // get data client
        $user = auth()->user();
        $pencariJasaMua = PencariJasaMua::where('user_id', $user->id)->first();
        $pencariJasaMua->foto = $this->formatFotoUrl($pencariJasaMua);

        // get Banner
        $banner = Banner::where('tanggal_mulai', '<=', date('Y-m-d H:i:s'))
            ->where('tanggal_selesai', '>=', date('Y-m-d H:i:s'))
            ->where('status', 'aktif')
            ->get();

        foreach ($banner as $key => $value) {
            $value->gambar = url('/banner/' . $value->gambar);
        }

        // get kategori
        $kategori = KategoriLayanan::all();
        $kategori->prepend([
            'id' => 0,
            'nama' => 'Semua',
        ]);

        $layananPopuler = Layanan::layananPopuler($limit);
        foreach ($layananPopuler as $key => $value) {
            $value->foto = $this->formatLayananUrl($value);
        }

        $kecamatanTerdekat = $this->getKecamatanTerdekat($pencariJasaMua->alamat);

        $layananTerdekat = Layanan::layananTerdekat($kecamatanTerdekat, $limit);



        foreach ($layananTerdekat as $key => $value) {
            $value->foto = $this->formatLayananUrl($value);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data dashboard client',
            'data' => [
                'client' => [
                    'id' => $pencariJasaMua->id,
                    'nama' => $pencariJasaMua->nama,
                    'foto' => $pencariJasaMua->foto,
                ],
                'banner' => $banner,
                'kategori' => $kategori->map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'nama' => $item['nama'],
                    ];
                }),
                'layanan_populer' => $layananPopuler->map(function ($item) {
                    return [
                        'id' => $item['id_mua'],
                        'nama' => $item['nama_jasa_mua'],
                        'foto' => $item['foto'],
                        'lokasi' => $item['nama_kecamatan']. ', Surabaya',
                    ];
                }),
                'layanan_terdekat' => $layananTerdekat->map(function ($item) {
                    return [
                        'id' => $item['id_mua'],
                        'nama' => $item['nama_jasa_mua'],
                        'foto' => $item['foto'],
                        'lokasi' => $item['nama_kecamatan']. ', Surabaya',
                    ];
                }),
            ]
        ]);
    }

    public function searchMua(Request $request)
    {
        $searchTerms = explode(' ', $request->input('search'));
    
        // Start with an empty query
        $muaQuery = PenyediaJasaMua::select('penyedia_jasa_mua.id', 'penyedia_jasa_mua.nama_jasa_mua', 'penyedia_jasa_mua.lokasi_jasa_mua', 'layanan.harga', 'penyedia_jasa_mua.foto', 'penyedia_jasa_mua.nama', 'kategori_layanan.nama as nama_kategori', 'kecamatan.nama_kecamatan', 'penyedia_jasa_mua.user_id')
            ->join('jasa_mua_kategori', 'jasa_mua_kategori.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
            ->join('kategori_layanan', 'kategori_layanan.id', '=', 'jasa_mua_kategori.kategori_layanan_id')
            ->join('layanan', 'layanan.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
            ->join('users', 'users.id', '=', 'penyedia_jasa_mua.user_id')
            ->join('kecamatan', 'kecamatan.id', '=', 'penyedia_jasa_mua.lokasi_jasa_mua');
    
        // Create a loop for each search term to apply conditions
        foreach ($searchTerms as $term) {
            $term = strtolower($term); // Convert the search term to lowercase
    
            $muaQuery->where(function ($query) use ($term) {
                $query->orWhereRaw("LOWER(penyedia_jasa_mua.nama_jasa_mua) LIKE ?", ['%' . $term . '%'])
                    ->orWhereRaw("LOWER(kecamatan.nama_kecamatan) LIKE ?", ['%' . $term . '%'])
                    ->orWhereRaw("LOWER(kategori_layanan.nama) LIKE ?", ['%' . $term . '%']);
                $query->orWhere(function ($subQuery) use ($term) {
                    // Search for prices within a range of +/- 50000
                    $subQuery->whereBetween('layanan.harga', [(int)$term - 50000, (int)$term + 50000]);
                });
            });
        }
    
        // Get the results
        $muaResults = $muaQuery->get();
    
        // Initialize an empty array to store grouped results
        $groupedResults = [];
    
        // Process the results and group them by 'id'
        foreach ($muaResults as $mua) {
            $id = $mua->id;
    
            if (!isset($groupedResults[$id])) {
                $groupedResults[$id] = [
                    'id' => $id,
                    'nama_jasa_mua' => $mua->nama_jasa_mua,
                    'lokasi_jasa_mua' => $mua->nama_kecamatan. ', Surabaya',
                    'harga' => PHP_INT_MAX,
                    'foto' => $this->formatFotoUrl($mua),
                    'jumlah_pemesanan' => 0,
                    'ulasan' => 0,
                    'nama_kategori' => [],
                ];
            }
    
            $groupedResults[$id]['nama_kategori'][] = $mua->nama_kategori;
    
            if ((int)$mua->harga < (int)$groupedResults[$id]['harga']) {
                $groupedResults[$id]['harga'] = (int)$mua->harga;
            }
    
            $groupedResults[$id]['jumlah_pemesanan'] += $this->getPemesananCount($id);
            $groupedResults[$id]['ulasan'] += $this->getUlasanAverage($id);
        }
    
        // Convert the grouped results into an indexed array
        $finalResults = array_values($groupedResults);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data pencarian mua',
            'data' => $finalResults,
        ]);
    }
    
    private function getPemesananCount($id)
    {
        return Pemesanan::where('penyedia_jasa_mua_id', $id)
            ->where('status', 'selesai')
            ->count();
    }
    
    private function getUlasanAverage($id)
    {
        return Ulasan::join('pemesanan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
            ->where('pemesanan.penyedia_jasa_mua_id', $id)
            ->where('pemesanan.status', 'selesai')
            ->avg('ulasan.rating');
    }
}
