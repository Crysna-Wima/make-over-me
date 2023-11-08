<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Models\PencariJasaMua;
use Illuminate\Http\Request;

class ProfileClientController extends Controller
{
    public function getProfileClient()
    {
        $user = auth()->user();
        $pencariJasaMua = PencariJasaMua::where('user_id', $user->id)->first();
        $pencariJasaMua->foto = $this->formatFotoUrl($pencariJasaMua);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data profile Client',
            'data' => $pencariJasaMua,
        ]);
    }
}
