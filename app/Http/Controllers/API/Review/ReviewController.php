<?php

namespace App\Http\Controllers\API\Review;

use App\Http\Controllers\Controller;
use App\Models\GaleriPembeli;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function createReview(Request $request){
        $validate = Validator::make($request->all(), [
            "rating"=> "required|integer|min:1|max:5",
            "ulasan" => "required|string",
            "pemesanan_id" => "required|exists:pemesanan,id",
            "gambar.*" => "nullable|max:2048",
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
            $review = Ulasan::create([
                'pemesanan_id' => $request->pemesanan_id,
                'penyedia_jasa_mua_id' => $pemesanan->penyedia_jasa_mua_id, // 'penyedia_jasa_mua_id' => $pemesanan->penyedia_jasa_mua_id,
                'rating' => $request->rating,
                'komentar' => $request->ulasan,
                'tanggal' => date('Y-m-d'),
            ]);

            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $directory = 'file/' . auth()->user()->id . "_" . auth()->user()->penyedia_jasa_mua->nama . '/review/';
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
                            'pencari_jasa_mua_id' => $pemesanan->pencari_jasa_mua_id,
                            'gambar' => $filename,
                            'deskripsi' => $request->ulasan,
                        ]);
                    }
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
}
