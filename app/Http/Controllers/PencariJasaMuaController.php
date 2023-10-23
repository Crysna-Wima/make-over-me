<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PencariJasaMuaController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'tanggal_lahir' => 'required',
            'lokasi' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_kelamin' => 'required',
            'status' => 'required',
            'user_id' => 'required',
        ]);
    }
}
