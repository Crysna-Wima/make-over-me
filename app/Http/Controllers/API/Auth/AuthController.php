<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\JamKetersediaan;
use App\Models\PencariJasaMua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PenyediaJasaMua;
use App\Models\Portofolio;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        // Create User
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);

            return response()->json(['status' => true, 'message' => 'User created successfully', 'data' => $user, 'token' => $user->createToken('Personal Access Token')->plainTextToken], 201); 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }
    
        // Attempt to log the user in
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }
    
        // Get the authenticated user
        $user = auth()->user();

        // check mua status is active
        if ($user->role_id == 3) {
            $penyediaJasaMua = PenyediaJasaMua::where('user_id', $user->id)->first();

            if ($penyediaJasaMua->status == 0) {
                return response()->json(['status' => false, 'message' => 'Akun anda belum aktif, silahkan hubungi admin'], 401);
            }
        }
        
        if($user->role_id == 2){
            return $this->formatPencariJasaMuaData($user);
        }else if($user->role_id == 3){
            return $this->formatPenyediaJasaMuaData($user);
        }
    }

    private function formatPenyediaJasaMuaData($user){
        $penyediaJasaMua = PenyediaJasaMua::where('user_id', $user->id)->first();
        $portofolioFiles = Portofolio::where('penyedia_jasa_mua_id', $penyediaJasaMua->id)->pluck('gambar');
        $portofolioUrls = formatPortofolioUrls($portofolioFiles, $penyediaJasaMua);
        $hariKetersediaan = JamKetersediaan::where('penyedia_jasa_mua_id', $penyediaJasaMua->id)->pluck('hari');
        $jasaMuaKategoriNames = getJasaMuaKategoriName($penyediaJasaMua);
        $user->foto = formatFotoUrl($penyediaJasaMua);
        return response()->json(['status'=> true,
            'message'=> 'Login successfully',
            'data'=> $user,
            'token'=> $user->createToken('Personal Access Token')->plainTextToken,
            'hari_ketersediaan'=> $hariKetersediaan,
            'jasa_mua_kategori_names'=> $jasaMuaKategoriNames,
            'portofolio_urls'=> $portofolioUrls
        ], 200);
    }

    private function formatPencariJasaMuaData($user){
        $pecariJasa = PencariJasaMua::where('user_id', $user->id)->first();
        $user->foto = formatFotoUrl($pecariJasa);
        return response()->json(['status'=> true,
            'message'=> 'Login successfully',
            'data'=> $user,
            'token'=> $user->createToken('Personal Access Token')->plainTextToken
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['status' => true, 'message' => 'Logout successfully'], 200);
    }
}
