<?php

namespace App\Http\Controllers\API\Pemesanan;

use App\Http\Controllers\Controller;
use App\Models\PenyediaJasaMua;
use App\Models\Pemesanan;
use App\Models\PencariJasaMua;
use App\Models\DetailPemesanan;
use App\Models\Ulasan;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PemesananController extends Controller
{
    public function cekPemesanan(Request $request)
    {
        if($request->tanggal_pemesanan <= date('Y-m-d')){
            return response()->json([
                'status' => 'failed',
                'message' => 'Silahkan memilih tanggal pemesanan yang sesuai',
            ]);
        }
        $penyediaJasaMua = PenyediaJasaMua::where('id', $request->id)->first();
        $pemesanan = $penyediaJasaMua->pemesanan()->where('status', '!=', 'done')
        ->where('tanggal_pemesanan', $request->tanggal_pemesanan)
        ->count();

        if ($pemesanan >= $penyediaJasaMua->kapasitas_pelanggan_per_hari) {
            return response()->json([
                'status' => 'failed',
                'message' => 'MUA yang anda inginkan sudah penuh pada tanggal tersebut',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Kuota pemesanan masih tersedia',
            ]);
        }
    }

    public function createPemesanan(Request $request){
        $validate = Validator::make($request->all(), [
            'id'=> 'required|exists:penyedia_jasa_mua,id',
            'tanggal_pemesanan' => 'required|date',
            'nama_pemesan' => 'required|string',
            'nomor_telepon_pemesan' => 'required|max:15',
            'gender_pemesan' => 'required|in:L,P',
            'keterangan' => 'nullable|string',
            'layanan_id' => 'required|exists:layanan,id',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|integer|min:0',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validate->errors(),
            ]);
        }

        $user = auth()->user();
        $pencariJasaMua = PencariJasaMua::where('user_id', $user->id)->first();
        $penyediaJasaMua = PenyediaJasaMua::where('id', $request->id)->first();

        DB::beginTransaction();
        try {
            $pemesanan = new Pemesanan();
            $pemesanan->pencari_jasa_mua_id = $pencariJasaMua->id;
            $pemesanan->penyedia_jasa_mua_id = $penyediaJasaMua->id;
            $pemesanan->tanggal_pemesanan = $request->tanggal_pemesanan;
            $pemesanan->nama_pemesan = $request->nama_pemesan;
            $pemesanan->nomor_telepon_pemesan = $request->nomor_telepon_pemesan;
            $pemesanan->gender_pemesan = $request->gender_pemesan;
            $pemesanan->keterangan = $request->keterangan;
            $pemesanan->status = 'pending';
            $pemesanan->save();

            $detailPemesanan = new DetailPemesanan();
            $detailPemesanan->pemesanan_id = $pemesanan->id;
            $detailPemesanan->layanan_id = $request->layanan_id;
            $detailPemesanan->jumlah = $request->jumlah;
            $detailPemesanan->total_harga = $request->total_harga;
            $detailPemesanan->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil melakukan pemesanan',
                'data' => array_merge($pemesanan->toArray(), $detailPemesanan->toArray()),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => 'Gagal melakukan pemesanan',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getPemesanan(Request $request){
        $user = auth()->user();
        $pencariJasaMua = PencariJasaMua::where('user_id', $user->id)->first();
        $pemesanan = $pencariJasaMua->pemesanan()->with('detailPemesanan.layanan')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data pemesanan',
            'data' => $pemesanan,
        ]);
    }
    
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

    public function getDetailPemesanan($id){
        $data = Pemesanan::join('detail_pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
        ->join('layanan', 'detail_pemesanan.layanan_id', '=', 'layanan.id')
        ->where('pemesanan.id', '=', $id)
        ->select('layanan.nama', 'pemesanan.tanggal_pemesanan', 'detail_pemesanan.jumlah', 'detail_pemesanan.total_harga', 'pemesanan.nama_pemesan', 'pemesanan.nomor_telepon_pemesan', 'pemesanan.gender_pemesan', 'pemesanan.keterangan')
        ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menampilkan detail pemesanan',
            'data' => $data,
        ], 200);
    }

    public function getPemesananDone(){
        $user = auth()->user();
        $pencariJasaMua = PencariJasaMua::where('user_id', $user->id)->first();
        $pemesanan = Pemesanan::join('penyedia_jasa_mua', 'penyedia_jasa_mua.id', '=', 'pemesanan.penyedia_jasa_mua_id')
            ->join('detail_pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
            ->join('layanan', 'detail_pemesanan.layanan_id', '=', 'layanan.id')
            ->join('kategori_layanan', 'kategori_layanan.id', '=', 'layanan.kategori_layanan_id')
            ->where('pemesanan.pencari_jasa_mua_id', '=', $pencariJasaMua->id)
            ->wherein('pemesanan.status', ['done', 'accept'])
            ->where('pemesanan.tanggal_pemesanan', '<', date('Y-m-d'))
            ->select('pemesanan.id', 'penyedia_jasa_mua.user_id','penyedia_jasa_mua.nama as nama', 'kategori_layanan.nama as jenis_jasa', 'pemesanan.tanggal_pemesanan', 'penyedia_jasa_mua.foto')
            ->get();
    
        foreach ($pemesanan as $key => $value) {
            // Ambil data review untuk pemesanan tertentu
            $review = Ulasan::where('pemesanan_id', '=', $value->id)
                ->select('ulasan.id', 'ulasan.rating', 'ulasan.komentar', 'ulasan.tanggal')
                ->first();
    
            if ($review) {
                // Jika review ditemukan, masukkan ke dalam kolom review pada objek pemesanan
                $pemesanan[$key]->review = $review;
            }
    
            $pemesanan[$key]->foto = $this->formatFotoUrl($value);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data pemesanan',
            'data' => $pemesanan,
        ]); 
    }
    
}
