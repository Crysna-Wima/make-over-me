<?php

namespace App\Http\Controllers\API\Layanan;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function getLayananMua($id)
    {
        $layanan = Layanan::where('penyedia_jasa_mua_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data layanan',
            'data' => $layanan,
        ]);
    }
}
