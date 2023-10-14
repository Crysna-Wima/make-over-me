<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenyediaJasaMuaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JamKetersediaan;
use App\Models\JasaMuaKategori;
use App\Models\PenyediaJasaMua;
use App\Models\Portofolio;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class PenyediaJasaMuaController extends Controller
{
    public function register(Request $request)
    {
        $validationRules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'tanggal_lahir' => 'required',
            'nama_jasa_mua' => 'required',
            'lokasi_jasa_mua' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'kapasitas_pelanggan_per_hari' => 'required',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        DB::beginTransaction();
    
        try {
            // Create User
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 3,
            ]);
    
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
            $this->createPortofolio($penyediaJasaMua, $request);
            $portofolio = Portofolio::where('penyedia_jasa_mua_id', $penyediaJasaMua)->get();
            foreach ($portofolio as $portofolio){
                $portofolio = url('/images/portofolio/'.$penyediaJasaMua->gambar);
            }
    
            // Create Jasa Mua Kategori
            $this->createJasaMuaKategori($penyediaJasaMua, $request);
            $jasaMuaKategori = JasaMuaKategori::join('kategori_layanan', 'jasa_mua_kategori.penyedia_jasa_mua_id', '=', 'kategori_layanan.id')->
                    where('penyedia_jasa_mua_id', $penyediaJasaMua)->select('nama')->get();

    
            // Create Hari Ketersediaan
            $this->createHariKetersediaan($penyediaJasaMua, $request);
            $hariKetersediaan = JamKetersediaan::where('penyedia_jasa_mua_id', $penyediaJasaMua)->select('hari')->get();
    
            DB::commit();
    
            return response()->json([
                'message' => 'Register Berhasil',
                'data' => [
                    'user' => $user,
                    'foto' => url('/images/penyedia_jasa_mua/'.$penyediaJasaMua->foto), // Image URL
                    'penyedia_jasa_mua' => $penyediaJasaMua,
                    'portofolio' => $portofolio,
                    'jasa_mua_kategori' => $jasaMuaKategori,
                    'hari_ketersediaan' => $hariKetersediaan,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Register Gagal',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    private function uploadImage($file)
    {
        if ($file) {
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('images/penyedia_jasa_mua/', $filename);
            return $filename;
        } else {
            return 'default.jpg';
        }
    }
    
    private function createPortofolio($penyediaJasaMua, $request)
    {
        if ($request->hasFile('portofolio')) {
            foreach ($request->file('portofolio') as $file) {
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('images/portofolio/', $filename);
    
                Portofolio::create([
                    'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                    'gambar' => $filename,
                ]);
            }
        }
    }
    
    private function createJasaMuaKategori($penyediaJasaMua, $request)
    {
        foreach ($request->kategori_layanan_id as $kategoriLayananId) {
            JasaMuaKategori::create([
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'kategori_layanan_id' => $kategoriLayananId,
            ]);
        }
    }
    
    private function createHariKetersediaan($penyediaJasaMua, $request)
    {
        foreach ($request->hari as $hari) {
            JamKetersediaan::create([
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'hari' => $hari,
            ]);
        }
    }
    
}
