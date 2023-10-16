<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriLayanan;

class KategoriLayananController extends Controller
{
    public function getKategoriLayanans(Request $request)
    {
        $kategoriLayanans = KategoriLayanan::select('id', 'nama')->get();

        return response()->json([
            'status' => true,
            'message' => 'Success get kategori layanans',
            'data' => $kategoriLayanans
        ]);
    }
}
