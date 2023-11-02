<?php

namespace App\Http\Controllers\API\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PenyediaJasaMua;
use App\Models\Portofolio;
use App\Models\JasaMuaKategori;
use App\Models\HariKetersediaan;

class RegisterMuaController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'tanggal_lahir' => 'required',
            'nama_jasa_mua' => 'required',
            'jenis_kelamin' => 'required',
            'lokasi_jasa_mua' => 'required|exists:kecamatan,id',
            'hari_ketersediaan' => 'required',
            'foto' => 'required',
            'portofolio.*' => 'required',
            'kapasitas_pelanggan_per_hari' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $user = auth()->user();

            // Parse data
            $hari_ketersediaan = $this->parseData($request->hari_ketersediaan);
            $kategori_layanan = $this->parseData($request->kategori_layanan);

            if ($user->role_id != 2) {
                return response()->json(['status' => false, 'message' => 'User tidak memiliki role penyedia jasa mua'], 422);
            }

            $penyediaJasaMua = PenyediaJasaMua::create([
                'nama' => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'gender' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nama_jasa_mua' => $request->nama_jasa_mua,
                'lokasi_jasa_mua' => $request->lokasi_jasa_mua,
                // make for base64 image
                'foto' => $this->uploadBase64Foto($request->foto, $user->id, $request->nama),
                'kapasitas_pelanggan_per_hari' => $request->kapasitas_pelanggan_per_hari,
                'status' => 1,
                'user_id' => $user->id,
            ]);

            $penyediaJasaMua->lokasi_jasa_mua = $penyediaJasaMua->kecamatan->nama_kecamatan.', Surabaya';
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

    public function accept(Request $request)
    {
        $penyediaJasaMua = PenyediaJasaMua::find($request->id_user);

        if (!$penyediaJasaMua) {
            return response()->json([
                'status' => 'error',
                'message' => 'Penyedia Jasa Mua tidak ditemukan',
            ], 404);
        }

        $penyediaJasaMua->status = 1;
        $penyediaJasaMua->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Penyedia Jasa Mua berhasil diaktifkan',
            'data' => $penyediaJasaMua,
        ], 200);
    }

    private function createPortofolio($penyediaJasaMua, $request)
    {
        $portofolioFiles = [];
    
        // Check if portofolio files are sent as base64 strings
        if ($request->has('portofolio')) {
            foreach ($request->portofolio as $base64Pdf) {
                $filename = $this->uploadBase64Portofolio($base64Pdf, $penyediaJasaMua->user_id, $penyediaJasaMua->nama);
    
                if ($filename) {
                    Portofolio::create([
                        'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                        'file' => $filename,
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
            HariKetersediaan::create([
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'hari' => $hariKetersediaan,
            ]);
        }
    }

    private function formatPenyediaJasaMuaData($user, $penyediaJasaMua, $portofolioFiles)
    {
        $data = [
            'user' => $user,
            'foto' => $this->formatFotoUrl($penyediaJasaMua),
            'penyedia_jasa_mua' => $penyediaJasaMua,
            'portofolio' => $this->formatPortofolioUrls($portofolioFiles, $penyediaJasaMua),
            'jasa_mua_kategori' => $this->getJasaMuaKategoriName($penyediaJasaMua),
            'hari_ketersediaan' => $this->getHariKetersediaan($penyediaJasaMua),
        ];
        return $data;
    }


}
