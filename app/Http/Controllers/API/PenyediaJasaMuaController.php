<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyediaJasaMuaController extends Controller
{
    //register
    public function register(Request $request)
    {
        $dataUser =[
            'username' => $request->username,
            'password' => $request->password,
            'email' => $request->email,
            'role_id' => 3,
        ];

        $dataPenyediaJasaMua = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
        ];

        DB::beginTransaction();
        try {
            $user = User::create($dataUser);
            $dataPenyediaJasaMua['user_id'] = $user->id;
            $penyediaJasaMua = PenyediaJasaMua::create($dataPenyediaJasaMua);
            DB::commit();
            return response()->json([
                'message' => 'Register Berhasil',
                'data' => $penyediaJasaMua
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Register Gagal',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    //login
    public function login(Request $request)
    {
        $dataUser = [
            'username' => $request->username,
            'password' => $request->password,
            'role_id' => 3,
        ];

        $user = User::where($dataUser)->first();
        if ($user) {
            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'message' => 'Login Berhasil',
                'data' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'message' => 'Login Gagal',
                'data' => 'Username atau Password Salah'
            ], 400);
        }
    }
}
