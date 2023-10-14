<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenyediaJasaMuaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [    
            'email' => 'required|email|unique:penyedia_jasa_mua,email',
            'password' => 'required|min:8',
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'tanggal_lahir' => 'required',
            'nama_jasa_mua' => 'required',
            'lokasi_jasa_mua' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'kapasitas_pelanggan_per_hari' => 'required',
        ];
    }
}
