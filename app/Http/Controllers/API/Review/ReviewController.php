<?php

namespace App\Http\Controllers\API\Review;

use App\Http\Controllers\Controller;
use App\Models\GaleriPembeli;
use App\Models\Pemesanan;
use App\Models\PenyediaJasaMua;
use App\Models\Ulasan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function createReview(Request $request){
        $validate = Validator::make($request->all(), [
            "rating"=> "required|integer|min:1|max:5",
            "ulasan" => "required|string",
            "pemesanan_id" => "required|exists:pemesanan,id",
            "gambar" => "nullable|array|max:5",
        ]);

        if($validate->fails()){
            return response()->json([
                "status" => "failed",
                "message" => $validate->errors(),
            ]);
        }

        DB::beginTransaction();
        try {
            $pemesanan = Pemesanan::where('id', $request->pemesanan_id)->first();
            if($pemesanan->status == 'done'){
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Pemesanan sudah direview',
                ]);
            }

            if ($pemesanan->tanggal_pemesanan > date('Y-m-d')) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Pemesanan belum selesai',
                ]);
            }
            
            $review = Ulasan::create([
                'pemesanan_id' => $request->pemesanan_id,
                'rating' => $request->rating,
                'komentar' => $request->ulasan,
                'tanggal' => date('Y-m-d'),
            ]);

            $pemesanan->status = 'done';
            $pemesanan->save();

            $mua = PenyediaJasaMua::where('id', $pemesanan->penyedia_jasa_mua_id)->first();
            foreach ($request->gambar as $file) {
                $directory = 'file/' . $mua->user_id . "_" . $mua->nama . '/review/';
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }
                if ($file) {
                    $foto = base64_decode($file);
                    if ($foto === false) {
                        return 'default.jpg';
                    }

                    $sourceImage = imagecreatefromstring($foto);

                    if ($sourceImage === false) {
                        return 'default.jpg';
                    }

                    $filename = time() . Str::random(10) . '.jpg';

                    if (imagejpeg($sourceImage, $directory . $filename)) {
                        $filename = $filename;
                    } else {
                        $filename = 'default.jpg';
                    }
                    $data = GaleriPembeli::create([
                        'ulasan_id' => $review->id,
                        'pencari_jasa_mua_id' => $pemesanan->pencari_jasa_mua_id,
                        'foto' => $filename,
                        'deskripsi' => $request->ulasan,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil melakukan review',
                'data' => $review,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'=> 'error',
                'message'=> $th->getMessage(),
                'data'=> $th->getTrace(),
            ]);
        }
    }

    public function getReview($id){
        $reviews = Pemesanan::where('pemesanan.id', $id)
            ->join('ulasan', 'pemesanan.id', '=', 'ulasan.pemesanan_id')
            ->join('galeri_pembeli', 'ulasan.id', '=', 'galeri_pembeli.ulasan_id')
            ->join('detail_pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
            ->join('layanan', 'detail_pemesanan.layanan_id', '=', 'layanan.id')
            ->join('kategori_layanan', 'layanan.kategori_layanan_id', '=', 'kategori_layanan.id')
            ->select('pemesanan.nama_pemesan', 'pemesanan.tanggal_pemesanan', 'kategori_layanan.nama as kategori', 'ulasan.rating', 'ulasan.komentar', 'galeri_pembeli.foto')
            ->get();

        $groupedReviews = [];

        foreach ($reviews as $key => $value) {
            $value->foto = url('file/' . auth()->user()->id . "_" . auth()->user()->penyedia_jasa_mua->nama . '/review/' . $value->foto);

            // Mengelompokkan berdasarkan kategori
            if (!isset($groupedReviews[$value->kategori])) {
                $groupedReviews[$value->kategori] = [
                    'nama_pemesan' => $value->nama_pemesan,
                    'tanggal_pemesanan' => $value->tanggal_pemesanan,
                    'kategori' => $value->kategori,
                    'rating' => $value->rating,
                    'komentar' => $value->komentar,
                    'foto' => [],
                ];
            }

            // Menambahkan foto ke dalam array
            $groupedReviews[$value->kategori]['foto'][] = $value->foto;
        }

        if($reviews->isNotEmpty()){
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan review',
                'data' => array_values($groupedReviews), // Mengubah kunci array menjadi indeks
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Belum ada review',
            ]);
        }
    }

    
    
    
    
}
