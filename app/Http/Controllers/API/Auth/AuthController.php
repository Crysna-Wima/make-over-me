<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\PencariJasaMua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PenyediaJasaMua;
use App\Models\Portofolio;
use App\Models\HariKetersediaan;
use Faker\Factory as FakerFactory;

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
            return $this->getResponse(false, $validator->errors(), 422);
        }

        try {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);

            if ($user->role_id == 3) {
                $pencariJasaMua = PencariJasaMua::where('user_id', $user->id)->first();
                if (!$pencariJasaMua) {
                    $faker = FakerFactory::create();
                    $pencariJasaMua = PencariJasaMua::create([
                        'nama' => explode('@', $user->email)[0],
                        'tanggal_lahir' =>  date('Y-m-d', strtotime('-18 years', strtotime(date('Y-m-d')))),
                        'gender' => $faker->randomElement(['L', 'P']),
                        'alamat' => $faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']),
                        'nomor_telepon' => $faker->randomElement(['081', '082', '083', '085', '087', '089']) . $faker->randomNumber(8),
                        'foto' => $this->uploadBase64Foto($this->generateDefaultAvatar(), $user->id, explode('@', $user->email)[0]),
                        'user_id' => $user->id
                    ]);
                }
            }

            return $this->getResponse(true, 'User created successfully', $user, $user->createToken('Personal Access Token')->plainTextToken, 201);
        } catch (\Exception $e) {
            return $this->getResponse(false, $e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return $this->getResponse(false, $validator->errors(), 422);
        }
    
        if (!auth()->attempt($request->only('email', 'password'))) {
            return $this->getResponse(false, 'Invalid credentials', 401);
        }
    
        $user = auth()->user();
    
        if ($user->role_id == 2) {
            $penyediaJasaMua = PenyediaJasaMua::where('user_id', $user->id)->first();
            if ($penyediaJasaMua->status == 0) {
                return $this->getResponse(false, 'Akun anda belum aktif, silahkan hubungi admin', 401);
            }
        }
    
        if ($user->role_id == 3) {
            $pencariJasaMua = PencariJasaMua::where('user_id', $user->id)->first();
            if (!$pencariJasaMua) {
                $faker = FakerFactory::create();
                $pencariJasaMua = PencariJasaMua::create([
                    'nama' => explode('@', $user->email)[0],
                    'tanggal_lahir' =>  date('Y-m-d', strtotime('-18 years', strtotime(date('Y-m-d')))),
                    'gender' => $faker->randomElement(['L', 'P']),
                    'alamat' => $faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']),
                    'nomor_telepon' => $faker->randomElement(['081', '082', '083', '085', '087', '089']) . $faker->randomNumber(8),
                    'foto' => $this->uploadBase64Foto($this->generateDefaultAvatar(), $user->id, explode('@', $user->email)[0]),
                    'user_id' => $user->id
                ]);
            }
            return $this->formatPencariJasaMuaData($user);
        } elseif ($user->role_id == 2) {
            return $this->formatPenyediaJasaMuaData($user);
        }
    }

    private function generateDefaultAvatar()
    {
        // Path ke gambar avatar default (gantilah dengan path yang sesuai)
        $avatarPath = public_path('default/default.jpg');
    
        // Baca file gambar dan konversi ke base64
        if (file_exists($avatarPath)) {
            $imageData = file_get_contents($avatarPath);
            return base64_encode($imageData);
        } else {
            return base64_encode(file_get_contents('https://via.placeholder.com/150'));
        }
    }

    private function createRandomHariKetersediaan($penyediaJasaMua)
    {
        $faker = FakerFactory::create();
        $hariKetersediaan = [];
        for ($i = 0; $i < 3; $i++) {
            $hariKetersediaan[] = [
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'hari' => $faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']),
            ];
        }
        // pastikan tidak ada hari yang sama
        $hariKetersediaan = array_unique($hariKetersediaan, SORT_REGULAR);
        shuffle($hariKetersediaan);

        HariKetersediaan::insert($hariKetersediaan);
    }

    private function createBlankPortofolio($penyediaJasaMua)
    {
        $faker = FakerFactory::create();
        $portofolio = [];
        for ($i = 0; $i < 2; $i++) {
            $portofolio[] = [
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'file' => $this->uploadBase64Portofolio($this->generateBlankPortofolio(), $penyediaJasaMua->user_id, $penyediaJasaMua->nama),
            ];
        }
        Portofolio::insert($portofolio);
    }

    private function generateBlankPortofolio()
    {
        // Path ke gambar avatar default (gantilah dengan path yang sesuai)
        $filePath = public_path('default/blank.pdf');
    
        // Baca file pdf dan konversi ke base64
        if (file_exists($filePath)) {
            $imageData = file_get_contents($filePath);
            return base64_encode($imageData);
        } else {
            return '';
        }
    }

    private function createRandomJasaMuaKategori($penyediaJasaMua)
    {
        $faker = FakerFactory::create();
        $jasaMuaKategori = [];
        for ($i = 0; $i < 2; $i++) {
            $jasaMuaKategori[] = [
                'penyedia_jasa_mua_id' => $penyediaJasaMua->id,
                'jasa_mua_kategori_id' => $faker->randomElement(['1', '2', '3', '4', '5']),
            ];
        }
        // pastikan tidak ada kategori yang sama
        $jasaMuaKategori = array_unique($jasaMuaKategori, SORT_REGULAR);
        shuffle($jasaMuaKategori);

        JasaMuaKategori::insert($jasaMuaKategori);
    }


    private function formatPenyediaJasaMuaData($user)
    {
        $penyediaJasaMua = PenyediaJasaMua::where('user_id', $user->id)->first();
        $portofolioFiles = Portofolio::where('penyedia_jasa_mua_id', $penyediaJasaMua->id)->pluck('file');
        $portofolioUrls = $this->formatPortofolioUrls($portofolioFiles, $penyediaJasaMua);
        $hariKetersediaan = HariKetersediaan::where('penyedia_jasa_mua_id', $penyediaJasaMua->id)->pluck('hari');
        $jasaMuaKategoriNames = $this->getJasaMuaKategoriName($penyediaJasaMua);
        $user->foto = $this->formatFotoUrl($penyediaJasaMua);
        return $this->getResponse(
            true,
            'Login successfully',
            $user,
            $user->createToken('Personal Access Token')->plainTextToken,
            200,
            ['hari_ketersediaan' => $hariKetersediaan, 'jasa_mua_kategori_names' => $jasaMuaKategoriNames, 'portofolio_urls' => $portofolioUrls]
        );
    }

    private function formatPencariJasaMuaData($user)
    {
        $pecariJasa = PencariJasaMua::where('user_id', $user->id)->first();
        $user->foto = $this->formatFotoUrl($pecariJasa);
        return $this->getResponse(true, 'Login successfully', $user, $user->createToken('Personal Access Token')->plainTextToken, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->getResponse(true, 'Logout successfully', null, 200);
    }

    private function getResponse($status, $message, $data = null, $token = null, $statusCode = 200, $additionalData = [])
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'token' => $token,
        ];

        return response()->json(array_merge($response, $additionalData), $statusCode);
    }
}
