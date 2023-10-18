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
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'tanggal_lahir' => 'required',
            'nama_jasa_mua' => 'required',
            'lokasi_jasa_mua' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'kapasitas_pelanggan_per_hari' => 'required',
            'portofolio.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        DB::beginTransaction();
    
        try {
            $user = auth()->user();
            
            // check role_id "penyedia jasa mua"
            if ($user->role_id != 3) {
                return response()->json(['status' => false, 'message' => 'User tidak memiliki role penyedia jasa mua'], 422);
            }

            // Create Penyedia Jasa Mua
            $penyediaJasaMua = PenyediaJasaMua::create([
                'nama' => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'gender' => $request->gender,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nama_jasa_mua' => $request->nama_jasa_mua,
                'lokasi_jasa_mua' => $request->lokasi_jasa_mua,
                'foto' => $this->uploadImage($request->file('foto')),
                'kapasitas_pelanggan_per_hari' => $request->kapasitas_pelanggan_per_hari,
                'status' => 0,
                'user_id' => $user->id,
            ]);
    
            // Create Portofolio
            $portofolioFiles = $this->createPortofolio($penyediaJasaMua, $request);
    
            // Create Jasa Mua Kategori
            $this->createJasaMuaKategori($penyediaJasaMua, $request);
    
            // Create Hari Ketersediaan
            $this->createHariKetersediaan($penyediaJasaMua, $request);
    
            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi Berhasil',
                'data' => [
                    'user' => $user,
                    'foto' => url('/images/penyedia_jasa_mua/'.$penyediaJasaMua->foto),
                    'penyedia_jasa_mua' => $penyediaJasaMua,
                    'portofolio' => $this->formatPortofolioUrls($portofolioFiles),
                    'jasa_mua_kategori' => $this->getJasaMuaKategoriNames($penyediaJasaMua),
                    'hari_ketersediaan' => $this->getHariKetersediaanDays($penyediaJasaMua),
                ]
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
    
    private function uploadImage($file)
    {
        if ($file) {
            $filename = time() . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move('images/penyedia_jasa_mua/', $filename);
            return $filename;
        } else {
            return 'default.jpg';
        }
    }
    
    private function createPortofolio($penyediaJasaMua, $request)
    {
        $portofolioFiles = [];
    
        if ($request->hasFile('portofolio')) {
            foreach ($request->file('portofolio') as $file) {
                $filename = time() . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move('images/portofolio/', $filename);
    
                Portofolio::create([
                    'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                    'gambar' => $filename,
                ]);
    
                $portofolioFiles[] = $filename;
            }
        }
    
        return $portofolioFiles;
    }
    

    private function createJasaMuaKategori($penyediaJasaMua, $request)
    {
        foreach ($request->kategori_layanan as $kategoriLayanan) {
            JasaMuaKategori::create([
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'kategori_layanan_id' => $kategoriLayanan,
            ]);
        }
    }

    private function createHariKetersediaan($penyediaJasaMua, $request)
    {
        foreach ($request->hari_ketersediaan as $hariKetersediaan) {
            JamKetersediaan::create([
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'hari' => $hariKetersediaan,
            ]);
        }
    }

    private function formatPortofolioUrls($portofolios)
    {
        $portofolioUrls = new Collection();
        foreach ($portofolios as $portofolio) {
            $portofolioUrls->push(url('/images/portofolio/'.$portofolio));
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
}
