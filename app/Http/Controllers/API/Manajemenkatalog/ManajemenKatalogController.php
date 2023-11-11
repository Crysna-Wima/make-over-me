<?php

namespace App\Http\Controllers\API\Manajemenkatalog;

use App\Http\Controllers\Controller;
use App\Models\GaleriPenjual;
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
}