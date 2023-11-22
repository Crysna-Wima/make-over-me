<?php

namespace App\Http\Controllers\API\Manajemenkatalog;

use App\Http\Controllers\Controller;
use App\Models\GaleriPenjual;
use App\Models\JasaMuaKategori;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManajemenKatalogController extends Controller
{
    public function getPreviewMua()
    {
        $data = GaleriPenjual::where('penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
            ->get();

        foreach ($data as $key => $value) {
            $data[$key]->foto = url('file/' . auth()->user()->id . "_" . auth()->user()->penyedia_jasa_mua->nama . '/galeri_penjual/' . $value->foto);
        }


        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }

    public function createPreviewMua(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $directory = 'file/' . auth()->user()->id . "_" . auth()->user()->penyedia_jasa_mua->nama . '/galeri_penjual/';
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            if ($request->foto) {
                $foto = base64_decode($request->foto);
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
                $data = GaleriPenjual::create([
                    'penyedia_jasa_mua_id' => auth()->user()->penyedia_jasa_mua->id,
                    'foto' => $filename,
                    'deskripsi' => $filename,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Menambahkan foto berhasil',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan foto',
                'error' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data',
            'data' => $data
        ]);
    }

    public function deletePreviewMua($id)
    {
        $data = GaleriPenjual::where('id', $id)->first();
        unlink('file/' . auth()->user()->id . "_" . auth()->user()->penyedia_jasa_mua->nama . '/galeri_penjual/' . $data->foto);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data',
            'data' => $data
        ]);
    }

    public function getKatalogJasa()
    {
        $data = JasaMuaKategori::join('kategori_layanan', 'jasa_mua_kategori.kategori_layanan_id', '=', 'kategori_layanan.id')
        ->where('jasa_mua_kategori.penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
        ->get();
        
        foreach ($data as $key => $value) {
            $data[$key]->foto = url('jasa/' . $value->foto);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan katalog jasa',
            'data' => $data
        ]);
    }

    public function getPreviewKatalog($id)
    {
        $data = JasaMuaKategori::join('kategori_layanan', 'jasa_mua_kategori.kategori_layanan_id', '=', 'kategori_layanan.id')
        ->join('layanan', 'kategori_layanan.id', '=', 'layanan.kategori_layanan_id')
        ->where('layanan.jasa_mua_kategori_id', $id)
        ->select('layanan.id', 'kategori_layanan.foto', 'kategori_layanan.nama', 'layanan.durasi', 'layanan.harga')
        ->first();
        
        $data->foto = url('jasa/' . $data->foto);
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil preview jasa',
            'data' => $data
        ]);
    }

    public function updateKatalogJasa(Request $request)
    {
        $data = Layanan::where('id', $request->id)->update([
            'harga'=>$request->harga,
            'durasi'=>$request->durasi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil edit katalog jasa',
            'data' => $data
        ]);
    }

    public function deleteKatalogJasa($id)
    {
        $data = Layanan::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil edit katalog jasa',
            'data' => $data
        ]);
    }

    public function getKategoriLayanan()
    {
        $kategorilayanan = KategoriLayanan::select('id', 'nama')->get();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menampilkan Kategori Layanan',
            'data' => $kategorilayanan
        ]);
    }
    
    public function createKatalogJasa(Request $request){

        $validator = Validator::make($request->all(), [
            'kategori_layanan_id' => 'required|exists:kategori_layanan,id',
            'durasi' => 'required',
            'harga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $jasamuakategori = JasaMuaKategori::create([
                'penyedia_jasa_mua_id' => auth()->user()->penyedia_jasa_mua->id,
                'kategori_layanan_id' => $request->kategori_layanan_id,
            ]);

            $directory = 'file/' . auth()->user()->id . "_" . auth()->user()->penyedia_jasa_mua->nama . '/layanan/';
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            if ($request->foto) {
                $foto = base64_decode($request->foto);
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
            }

            $layanan = Layanan::create([
                'kategori_layanan_id' => $jasamuakategori->kategori_layanan_id,
                'penyedia_jasa_mua_id' => auth()->user()->penyedia_jasa_mua->id,
                'jasa_mua_kategori_id' => $jasamuakategori->id,
                'nama' => $request->nama,
                'harga' => $request->harga,
                'foto' => $filename,
                'deskripsi' => $request->deskripsi,
                'durasi' => $request->durasi,
            ]);
        
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menambahkan Katalog Jasa',
               'jasa_mua_kategori' => $jasamuakategori,
                'layanan' => $layanan
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
}