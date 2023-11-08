<?php

namespace App\Http\Controllers\API\Pemesanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pemesanan;
use App\Models\PenyediaJasaMua;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function acceptPemesanan($id)
    {
        $pemesanan = Pemesanan::find($id);
        $pemesanan->status = 'accept';
        $pemesanan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pemesanan Telah Di Terima',
            'data' => $pemesanan,
        ], 200);
    }

    public function declinePemesanan($id)
    {
        $pemesanan = Pemesanan::find($id);
        $pemesanan->status = 'decline';
        $pemesanan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pemesanan Telah Di Tolak',
            'data' => $pemesanan,
        ], 200);
    }
}
