<?php

namespace App\Http\Controllers\API\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PencariJasaMua;

class RegisterClientController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required|exists:kecamatan,id',
            'nomor_telepon' => 'required',
            'foto' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $user = auth()->user();

            if ($user->role_id != 3) {
                return response()->json(['status' => false, 'message' => 'User tidak memiliki role client'], 422);
            }

            $pencariJasaMua = PencariJasaMua::create([
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'gender' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'nomor_telepon' => $request->nomor_telepon,
                'foto' => $this->uploadBase64Foto($request->foto, $user->id, $request->nama),
                'user_id' => $user->id
            ]);

            DB::commit();

           return response()->json([
                'status' => 'success',
                'message' => 'Registrasi Berhasil',
                'data' => $this->formatPencariJasaMua($pencariJasaMua)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error', 
                'message' => 'Registrasi Gagal',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    private function formatPencariJasaMua($pencariJasaMua)
    {
        return [
            'id' => $pencariJasaMua->id,
            'nama' => $pencariJasaMua->nama,
            'tanggal_lahir' => $pencariJasaMua->tanggal_lahir,
            'jenis_kelamin' => $pencariJasaMua->gender,
            'alamat' => $pencariJasaMua->kecamatan->nama_kecamatan.', Surabaya',
            'nomor_telepon' => $pencariJasaMua->nomor_telepon,
            'foto' => $this->formatFotoUrl($pencariJasaMua),
            'user_id' => $pencariJasaMua->user_id,
            'user' => $pencariJasaMua->user,
        ];
    }
}
