<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JamKetersediaan;
use App\Models\Portofolio;
use App\Models\JasaMuaKategori;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $user = auth()->user();
    
        // Check if the user has the role "Penyedia Jasa Mua"
        if ($user->hasRole && $user->hasRole->name === 'Penyedia Jasa Mua') {
            $penyediaJasaMua = $user->penyediaJasaMua;
            
            // Retrieve additional data specific to Penyedia Jasa Mua
            $hariKetersediaan = $penyediaJasaMua->jamKetersediaan;
            $portofolio = $penyediaJasaMua->portofolio;
            $kategoriLayanan = $penyediaJasaMua->jasaMuaKategori;
    
            return response()->json([
                'status' => true,
                'message' => 'Success get profile',
                'data' => [
                    'user' => $user,
                    'penyedia_jasa_mua' => $penyediaJasaMua,
                    'hari_ketersediaan' => $hariKetersediaan,
                    'portofolio' => $portofolio,
                    'kategori_layanan' => $kategoriLayanan,
                ]
            ]);
        } else {
            // If the user doesn't have the role "Penyedia Jasa Mua," return basic user data
            return response()->json([
                'status' => true,
                'message' => 'Success get profile',
                'data' => $user
            ]);
        }
    }
    
    

    private function getHariKetersediaan($user)
    {
        $hariKetersediaan = JamKetersediaan::where('penyedia_jasa_mua_id', $user->penyedia_jasa_mua->id)
            ->pluck('hari');
    
        return $hariKetersediaan;
    }

    private function getPortofolio($user)
    {
        $portofolios = Portofolio::where('penyedia_jasa_mua_id', $user->penyedia_jasa_mua->id)
            ->get();
    
        $portofolioUrls = $portofolios->map(function ($portofolio) {
            return url('/images/portofolio/' . $portofolio->gambar);
        });
    
        return $portofolioUrls;
    }
    

    private function getKategoriLayanan($user)
    {
        $kategoriLayanan = JasaMuaKategori::where('penyedia_jasa_mua_id', $user->penyedia_jasa_mua->id)
            ->join('kategori_layanan', 'jasa_mua_kategori.kategori_layanan_id', '=', 'kategori_layanan.id')
            ->pluck('kategori_layanan.nama');
    
        return $kategoriLayanan;
    }
    
}
