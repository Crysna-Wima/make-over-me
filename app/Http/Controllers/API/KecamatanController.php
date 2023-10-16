<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kecamatan;

class KecamatanController extends Controller
{
    public function getKecamatans(Request $request)
    {
        $kecamatans = Kecamatan::select('id', 'nama_kecamatan')->get();
        // Add 'Surabaya' to the end of each kecamatan name
        foreach ($kecamatans as $kecamatan) {
            $kecamatan->nama_kecamatan = $kecamatan->nama_kecamatan . ', Surabaya';
        }

        return response()->json([
            'status' => true,
            'message' => 'Success get kecamatans',
            'data' => $kecamatans
        ]);
    }
}
