<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\PencariJasaMua;
use App\Models\PenyediaJasaMua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardClientController extends Controller
{
    public function index($limit = null)
    {
        // get data client
        $user = auth()->user();
        $pencariJasaMua = PencariJasaMua::where('user_id', $user->id)->first();
        $pencariJasaMua->foto = formatFotoUrl($pencariJasaMua);

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

        $layananPopuler = $this->getLayananPopuler($limit);
        foreach ($layananPopuler as $key => $value) {
            $value->foto = formatLayananUrl($value);
        }

        $kecamatanTerdekat = getKecamatanTerdekat($pencariJasaMua->alamat);

        $layananTerdekat = $this->getLayananTerdekat($limit, $kecamatanTerdekat);



        foreach ($layananTerdekat as $key => $value) {
            $value->foto = formatLayananUrl($value);
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
                        'id' => $item['id'],
                        'nama' => $item['nama_jasa_mua'],
                        'foto' => formatLayananUrl($item),
                        'lokasi' => $item['lokasi_jasa_mua'],
                    ];
                }),
                'layanan_terdekat' => $layananTerdekat->map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'nama' => $item['nama_jasa_mua'],
                        'foto' => formatLayananUrl($item),
                        'lokasi' => $item['lokasi_jasa_mua'],
                    ];
                }),
            ]
        ]);
    }

    private function getLayananPopuler($limit)
    {
        // Query untuk mendapatkan data layanan, penyedia jasa, dan ulasan
        $query = Layanan::join('penyedia_jasa_mua', 'penyedia_jasa_mua.id', '=', 'layanan.penyedia_jasa_mua_id')
            ->get();

        // cek apakah ada layanan yang memiliki pemesanan
        foreach ($query as $key => $value) {
            $value->jumlah_pemesanan = DB::table('detail_pemesanan')
                ->join('pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
                ->where('detail_pemesanan.layanan_id', $value->id)
                ->where('pemesanan.status', 'selesai')
                ->count();
        }

        // jika memiliki pemesanan cek apakah ada ulasan lalu hitung rata-rata rating
        foreach ($query as $key => $value) {
            if ($value->jumlah_pemesanan > 0) {
                $value->ulasan = DB::table('detail_pemesanan')
                    ->join('pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
                    ->join('ulasan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
                    ->where('detail_pemesanan.layanan_id', $value->id)
                    ->where('pemesanan.status', 'selesai')
                    ->avg('ulasan.rating');
            } else {
                $value->ulasan = 0;
            }
        }

        // Urutkan berdasarkan jumlah pemesanan
        $query = $query->sortByDesc('jumlah_pemesanan');
        // Urutkan berdasarkan rating
        $query = $query->sortByDesc('ulasan');

        // Limit hasil ke $limit
        $query = $query->take($limit);

        // Return hasil
        return $query;
    }

    private function getLayananTerdekat($limit, $kecamatanTerdekat)
    {
        // Query untuk mendapatkan data layanan, penyedia jasa, dan ulasan
        $query = Layanan::join('penyedia_jasa_mua', 'penyedia_jasa_mua.id', '=', 'layanan.penyedia_jasa_mua_id')
            ->whereIn('penyedia_jasa_mua.lokasi_jasa_mua', $kecamatanTerdekat)->get();

        // cek apakah ada layanan yang memiliki pemesanan
        foreach ($query as $key => $value) {
            $value->jumlah_pemesanan = DB::table('detail_pemesanan')
                ->join('pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
                ->where('detail_pemesanan.layanan_id', $value->id)
                ->where('pemesanan.status', 'selesai')
                ->count();
        }

        // jika memiliki pemesanan cek apakah ada ulasan lalu hitung rata-rata rating
        foreach ($query as $key => $value) {
            if ($value->jumlah_pemesanan > 0) {
                $value->ulasan = DB::table('detail_pemesanan')
                    ->join('pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
                    ->join('ulasan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
                    ->where('detail_pemesanan.layanan_id', $value->id)
                    ->where('pemesanan.status', 'selesai')
                    ->avg('ulasan.rating');
            } else {
                $value->ulasan = 0;
            }
        }

        // Urutkan berdasarkan jumlah pemesanan
        $query = $query->sortByDesc('jumlah_pemesanan');
        // Urutkan berdasarkan rating
        $query = $query->sortByDesc('ulasan');

        // Limit hasil ke $limit
        $query = $query->take($limit);

        // Return hasil
        return $query;
    }

    public function searchMua(Request $request)
    {
        $searchTerms = explode(' ', $request->input('search'));

        // Start with an empty query
        $muaQuery = PenyediaJasaMua::join('jasa_mua_kategori', 'jasa_mua_kategori.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
            ->join('kategori_layanan', 'kategori_layanan.id', '=', 'jasa_mua_kategori.kategori_layanan_id')
            ->join('layanan', 'layanan.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
            ->select('penyedia_jasa_mua.id', 'penyedia_jasa_mua.nama_jasa_mua', 'penyedia_jasa_mua.lokasi_jasa_mua', 'kategori_layanan.nama', 'layanan.harga', 'layanan.foto');

        // Create a loop for each search term to apply conditions
        foreach ($searchTerms as $term) {
            $term = strtolower($term); // Convert the search term to lowercase

            $muaQuery->where(function ($query) use ($term) {
                $query->orWhereRaw("LOWER(penyedia_jasa_mua.nama_jasa_mua) LIKE ?", ['%' . $term . '%'])
                    ->orWhereRaw("LOWER(penyedia_jasa_mua.lokasi_jasa_mua) LIKE ?", ['%' . $term . '%'])
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
            $result = [
                'id' => $id,
                'nama_jasa_mua' => $mua->nama_jasa_mua,
                'lokasi_jasa_mua' => $mua->lokasi_jasa_mua,
                'nama' => [],
                'harga' => 'Mulai dari ' . PHP_INT_MAX,
                'foto' => $mua->foto,
                'jumlah_pemesanan' => 0,
                'ulasan' => 0,
            ];

            if (!isset($groupedResults[$id])) {
                $groupedResults[$id] = $result;
            }

            $groupedResults[$id]['nama'][] = [
                'id' => $mua->id,
                'nama' => $mua->nama,
            ];

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



    // Helper function to get the number of bookings for a given service
    private function getPemesananCount($layananId)
    {
        return DB::table('detail_pemesanan')
            ->join('pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
            ->where('detail_pemesanan.layanan_id', $layananId)
            ->count();
    }

    // Helper function to get the average rating for a given service
    private function getUlasanAverage($layananId)
    {
        return DB::table('detail_pemesanan')
            ->join('pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
            ->join('ulasan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
            ->where('detail_pemesanan.layanan_id', $layananId)
            ->avg('ulasan.rating');
    }
}
