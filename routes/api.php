<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route get kecamatans
Route::get('/kecamatans', 'API\KecamatanController@getKecamatans');
//Route get kategori layanans
Route::get('/kategori-layanans', 'API\KategoriLayananController@getKategoriLayanans');
// Route register user
Route::post('/register', 'API\AuthController@register');
//Route register Penyedia Jasa MUA
Route::middleware('auth:sanctum')->post('/penyedia-jasa-mua/register', 'API\PenyediaJasaMuaController@register');
//Route login
Route::post('/login', 'API\AuthController@login');
//Route get profile
Route::middleware('auth:sanctum')->get('/profile', 'API\ProfileController@getProfile');
//Route logout
Route::middleware('auth:sanctum')->post('/logout', 'API\AuthController@logout');
