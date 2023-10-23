<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JamKetersediaan;
use App\Models\JasaMuaKategori;
use App\Models\PenyediaJasaMua;
use App\Models\Portofolio;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class PenyediaJasaMuaController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'tanggal_lahir' => 'required',
            'nama_jasa_mua' => 'required',
            'jenis_kelamin' => 'required',
            'lokasi_jasa_mua' => 'required',
            'hari_ketersediaan' => 'required',
            'foto' => 'required',
            'portofolio.*' => 'required',
            // 'foto' => 'required|base64image|base64mimes:jpeg,png,jpg|max:2048',
            // 'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'kapasitas_pelanggan_per_hari' => 'required',
            // 'portofolio.*' => 'required|base64mimes:pdf|max:2048',
            // 'portofolio.*' => 'required|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $user = auth()->user();

            // Parse the hari_ketersediaan
            $hari_ketersediaan = $this->parseHariKetersediaan($request->hari_ketersediaan);
            // Parse the kategori_layanan
            $kategori_layanan = $this->parseKategoriLayanan($request->kategori_layanan);

            if ($user->role_id != 3) {
                return response()->json(['status' => false, 'message' => 'User tidak memiliki role penyedia jasa mua'], 422);
            }

            $penyediaJasaMua = $this->createPenyediaJasaMua($request, $user);

            $portofolioFiles = $this->createPortofolio($penyediaJasaMua, $request);
            $this->createJasaMuaKategori($penyediaJasaMua, $kategori_layanan);
            $this->createHariKetersediaan($penyediaJasaMua, $hari_ketersediaan);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi Berhasil',
                'data' => $this->formatPenyediaJasaMuaData($user, $penyediaJasaMua, $portofolioFiles),
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'registrasi Gagal',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    private function parseHariKetersediaan($hariKetersediaan)
    {
        $hariKetersediaan = str_replace(['[', ']', ' '], '', $hariKetersediaan);
        return explode(',', $hariKetersediaan);
    }

    private function parseKategoriLayanan($kategoriLayanan)
    {
        $kategoriLayanan = str_replace(['[', ']', ' '], '', $kategoriLayanan);
        return explode(',', $kategoriLayanan);
    }

    private function createPenyediaJasaMua($request, $user)
    {
        return PenyediaJasaMua::create([
            'nama' => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
            'gender' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nama_jasa_mua' => $request->nama_jasa_mua,
            'lokasi_jasa_mua' => $request->lokasi_jasa_mua,
            // make for base64 image
            'foto' => $this->uploadBase64Image($request->foto, $user->id, $request->nama_jasa_mua),
            // 'foto' => $this->uploadImage($request->file('foto'), $request, $user->id),
            'kapasitas_pelanggan_per_hari' => $request->kapasitas_pelanggan_per_hari,
            'status' => 0,
            'user_id' => $user->id,
        ]);
    }

    private function uploadImage($file, $request, $userId)
    {
        if ($file) {
            $nama_jasa_mua = $userId . "_" . $request->nama_jasa_mua;
            $filename = time() . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move('file/' . $nama_jasa_mua . '/foto/', $filename);
            return $filename;
        } else {
            return 'default.jpg';
        }
    }

    private function uploadBase64Image($base64Image, $userId, $namaJasaMua)
    {
        // Define the directory path
        $directory = 'file/' . $userId . "_" . $namaJasaMua . '/foto/';
    
        // Check if the directory exists; if not, create it.
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    
        if ($base64Image) {
            // Extract the image data from the base64 string
            $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
            $imageData = base64_decode($base64Image);
    
            if ($imageData === false) {
                return 'default.jpg'; // Handle decoding error here
            }
    
            // Generate a unique filename
            $filename = time() . Str::random(10) . '.jpeg';
    
            // Save the image to the specified folder
            if (file_put_contents($directory . $filename, $imageData)) {
                return $filename;
            } else {
                return 'default.jpg'; // Handle file saving error here
            }
        } else {
            return 'default.jpg'; // Handle missing image data
        }
    }
    


    // private function createPortofolio($penyediaJasaMua, $request)
    // {
    //     $portofolioFiles = [];

    //     if ($request->hasFile('portofolio')) {
    //         foreach ($request->file('portofolio') as $file) {
    //             $filename = time() . Str::random(10) . '.' . $file->getClientOriginalExtension();
    //             $file->move('file/' . $penyediaJasaMua->user_id . "_" . $penyediaJasaMua->nama_jasa_mua . '/portofolio/', $filename);

    //             Portofolio::create([
    //                 'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
    //                 'gambar' => $filename,
    //             ]);

    //             $portofolioFiles[] = $filename;
    //         }
    //     }

    //     return $portofolioFiles;
    // }

    private function uploadBase64Portofolio($base64Pdf, $userId, $namaJasaMua)
    {
        // Define the directory path
        $directory = 'file/' . $userId . "_" . $namaJasaMua . '/portofolio/';
    
        // Check if the directory exists; if not, create it.
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    
        if ($base64Pdf) {
            // Extract the PDF data from the base64 string
            $base64Pdf = str_replace('data:application/pdf;base64,', '', $base64Pdf);
            $pdfData = base64_decode($base64Pdf);
    
            if ($pdfData === false) {
                return null; // Handle decoding error here
            }
    
            // Generate a unique filename with a .pdf extension
            $filename = time() . Str::random(10) . '.pdf';
    
            // Save the PDF to the specified folder
            if (file_put_contents($directory . $filename, $pdfData)) {
                return $filename;
            } else {
                return null; // Handle file saving error here
            }
        } else {
            return null; // Handle missing PDF data
        }
    }
    

    private function createPortofolio($penyediaJasaMua, $request)
    {
        $portofolioFiles = [];
    
        // Check if portofolio files are sent as base64 strings
        if ($request->has('portofolio')) {
            foreach ($request->portofolio as $base64Pdf) {
                $filename = $this->uploadBase64Portofolio($base64Pdf, $penyediaJasaMua->user_id, $penyediaJasaMua->nama_jasa_mua);
    
                if ($filename) {
                    Portofolio::create([
                        'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                        'gambar' => $filename,
                    ]);
    
                    $portofolioFiles[] = $filename;
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'portofolio Gagal',
                        'error' => 'Gagal mengupload portofolio'
                    ], 400);
                }
            }
        }
    
        return $portofolioFiles;
    }
    


    private function createJasaMuaKategori($penyediaJasaMua, $kategori_layanan)
    {
        foreach ($kategori_layanan as $kategoriLayanan) {
            JasaMuaKategori::create([
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'kategori_layanan_id' => $kategoriLayanan,
            ]);
        }
    }

    private function createHariKetersediaan($penyediaJasaMua, $hariKetersediaan)
    {
        foreach ($hariKetersediaan as $hariKetersediaan) {
            JamKetersediaan::create([
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'hari' => $hariKetersediaan,
            ]);
        }
    }

    private function formatPenyediaJasaMuaData($user, $penyediaJasaMua, $portofolioFiles)
    {
        $data = [
            'user' => $user,
            'foto' => url('/file/' . $penyediaJasaMua->user_id . "_" . $penyediaJasaMua->nama_jasa_mua . '/foto/' . $penyediaJasaMua->foto),
            'penyedia_jasa_mua' => $penyediaJasaMua,
            'portofolio' => $this->formatPortofolioUrls($portofolioFiles, $penyediaJasaMua),
            'jasa_mua_kategori' => $this->getJasaMuaKategoriNames($penyediaJasaMua),
            'hari_ketersediaan' => $this->getHariKetersediaanDays($penyediaJasaMua),
        ];
        return $data;
    }

    private function formatPortofolioUrls($portofolios, $penyediaJasaMua)
    {
        $portofolioUrls = new Collection();
        foreach ($portofolios as $portofolio) {
            $portofolioUrls->push(url('/file/' . $penyediaJasaMua->user_id . "_" . $penyediaJasaMua->nama_jasa_mua . '/portofolio/' . $portofolio));
        }
        return $portofolioUrls;
    }

    private function getJasaMuaKategoriNames($penyediaJasaMua)
    {
        $jasaMuaKategoriNames = JasaMuaKategori::join('kategori_layanan', 'jasa_mua_kategori.kategori_layanan_id', '=', 'kategori_layanan.id')
            ->where('penyedia_jasa_mua_id', $penyediaJasaMua->id)
            ->pluck('kategori_layanan.nama');

        return $jasaMuaKategoriNames;
    }

    private function getHariKetersediaanDays($penyediaJasaMua)
    {
        $hariKetersediaanDays = JamKetersediaan::where('penyedia_jasa_mua_id', $penyediaJasaMua->id)
            ->pluck('hari');

        return $hariKetersediaanDays;
    }

    public function search(Request $request)
    {
        $search = explode(' ', $request->input('query'));

        $penyediaJasaMuas = PenyediaJasaMua::join('penyedia_jasa_mua_kategori', 'penyedia_jasa_mua.id', '=', 'penyedia_jasa_mua_kategori.penyedia_jasa_mua_id')
            ->join('kategori_layanan', 'penyedia_jasa_mua_kategori.kategori_layanan_id', '=', 'kategori_layanan.id')
            ->where('penyedia_jasa_mua.status', 1)
            ->where(function ($query) use ($search) {
                foreach ($search as $s) {
                    $query->orWhere('penyedia_jasa_mua.nama', 'like', '%' . $s . '%');
                    $query->orWhere('penyedia_jasa_mua.nama_jasa_mua', 'like', '%' . $s . '%');
                    $query->orWhere('penyedia_jasa_mua.lokasi_jasa_mua', 'like', '%' . $s . '%');
                    $query->orWhere('kategori_layanan.nama', 'like', '%' . $s . '%');
                    $query->orWhereBetween('penyedia_jasa_mua.harga', [$s, $s + 100000]);
                }
            })
            ->select('penyedia_jasa_mua.*')
            ->distinct()
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data penyedia jasa mua berhasil ditemukan',
            'data' => $penyediaJasaMuas,
        ], 200);
    }
}
