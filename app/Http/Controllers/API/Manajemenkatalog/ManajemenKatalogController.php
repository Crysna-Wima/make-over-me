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

use function Laravel\Prompts\error;

class ManajemenKatalogController extends Controller
{
    public function getPreviewMua()
    {
        $data = GaleriPenjual::where('penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
            ->get();

        if($data->isEmpty()){
            $data = [
                'id' => 0, // id 0 untuk menandakan bahwa data kosong, sehingga bisa dihandle di frontend dengan menampilkan foto default 'banner.jpeg
                'penyedia_jasa_mua_id' => auth()->user()->penyedia_jasa_mua->id,
                'foto' => url('default/banner.jpeg'),
                'deskripsi' => 'foto default',
                'created_at' => '',
                'updated_at' => '',
            ];
	
          $data = [$data];
        } else {
            foreach ($data as $key => $value) {
                $data[$key]->foto = url('file/' . auth()->user()->id . '/galeri_penjual/' . $value->foto);
            }
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
            $directory = 'file/' . auth()->user()->id . '/galeri_penjual/';
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
        // unlink('file/' . auth()->user()->id . '/galeri_penjual/' . $data->foto);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data',
            'data' => $data
        ]);
    }

    public function getKatalogJasa()
    {
        $data = Layanan::join('jasa_mua_kategori', 'jasa_mua_kategori.id', '=', 'layanan.jasa_mua_kategori_id')
        ->join('kategori_layanan', 'kategori_layanan.id', '=', 'layanan.kategori_layanan_id')
        ->where('layanan.penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
        ->select('layanan.id', 'kategori_layanan.nama', 'layanan.foto')
        ->get();
        
        foreach ($data as $key => $value) {
            $data[$key]->foto = url('file/'. auth()->user()->penyedia_jasa_mua->user_id . '/layanan/' .$data[$key]->foto);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan katalog jasa',
            'data' => $data
        ]);
    }

    public function getPreviewKatalog($id)
    {
        $data = Layanan::join('jasa_mua_kategori', 'jasa_mua_kategori.id', '=', 'layanan.jasa_mua_kategori_id')
        ->join('kategori_layanan', 'kategori_layanan.id', '=', 'layanan.kategori_layanan_id')
        ->where('layanan.penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
        ->where('layanan.id', '=', $id)
        ->select('layanan.id', 'layanan.foto', 'kategori_layanan.nama', 'layanan.durasi', 'layanan.harga')
        ->first();
        
        $data->foto = url('file/'. auth()->user()->penyedia_jasa_mua->user_id . '_' . auth()->user()->penyedia_jasa_mua->nama . '/layanan/' .$data->foto);
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil preview katalog jasa',
            'data' => $data
        ]);
    }

    public function updateKatalogJasa(Request $request)
    {
        $data = Layanan::where('layanan.id', $request->id)->update([
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

        DB::beginTransaction();

        try {
            $data_layanan = Layanan::where('id', $id)->first();
        $data_jasamuakategori = JasaMuaKategori::where('id', $data_layanan->jasa_mua_kategori_id)->first();
        $data_layanan->delete();
        $data_jasamuakategori->delete();
        unlink('file/'. auth()->user()->penyedia_jasa_mua->user_id . '_' . auth()->user()->penyedia_jasa_mua->nama . '/layanan/' .$data_layanan->foto);

        DB::commit();
        
        return response()->json([
            'status' => true,
            'message' => 'Berhasil hapus katalog jasa',
            'datalayanan' => $data_layanan,
            'datajasamuakategori' => $data_jasamuakategori,
        ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal hapus katalog jasa',
            ]);
        }
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
        
        // jika kategori layanan sudah ada dengan penyedia jasa mua yang sama
        // $cek = JasaMuaKategori::where('penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
        //     ->where('kategori_layanan_id', $request->kategori_layanan_id)
        //     ->first();
        
        // if ($cek) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Gagal Menambahkan Katalog Jasa, Katalog Jasa Sudah Ada',
        //     ], 400);
        // }

        DB::beginTransaction();

        try {
            $jasamuakategori = JasaMuaKategori::create([
                'penyedia_jasa_mua_id' => auth()->user()->penyedia_jasa_mua->id,
                'kategori_layanan_id' => $request->kategori_layanan_id,
            ]);

            $directory = 'file/' . auth()->user()->id . '/layanan/';
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