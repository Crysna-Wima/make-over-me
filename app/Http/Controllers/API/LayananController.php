<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Layanan;

class LayananController extends Controller
{
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'harga' => 'required',
            'foto' => 'required',
            'deskripsi' => 'required',
            'kategori_layanan_id' => 'required',
        ]);
    
        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Semua field harus diisi',
                'data' => $validator->errors()
            ], 400);
        }
    
        $layanan = Layanan::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'foto' => $this->processAndSaveImage($request->foto, auth()->user()->id, auth()->user()->penyediaJasaMua->nama),
            'deskripsi' => $request->deskripsi,
            'kategori_layanan_id' => $request->kategori_layanan_id,
            'penyedia_jasa_mua_id' => auth()->user()->penyediaJasaMua->id
        ]);
    
        $layanan->foto = url('file/' . auth()->user()->id . "_" . auth()->user()->penyediaJasaMua->nama_jasa_mua . '/layanan/' . $layanan->foto);
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan layanan',
            'data' => $layanan
        ]);
    }
    
    private function processAndSaveImage($base64Image, $userId, $namaJasaMua) {
        // Define the directory path
        $directory = 'file/' . $userId . "_" . $namaJasaMua . '/layanan/';
    
        // Check if the directory exists; if not, create it.
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    
        if ($base64Image) {
            // Extract the image data from the base64 string
            $imageData = base64_decode($base64Image);
    
            if ($imageData === false) {
                return 'default.jpg'; // Handle decoding error here
            }
    
            // Create an image resource from the decoded data
            $sourceImage = imagecreatefromstring($imageData);
    
            if ($sourceImage === false) {
                return 'default.jpg'; // Handle image creation error here
            }
    
            // Generate a unique filename with .jpg extension
            $filename = time() . Str::random(10) . '.jpg';
    
            // Save the image as a JPG file
            if (imagejpeg($sourceImage, $directory . $filename, 100)) {
                return $filename;
            } else {
                return 'default.jpg'; // Handle image saving error here
            }
        } else {
            return 'default.jpg'; // Handle missing image data
        }
    }
    
}
