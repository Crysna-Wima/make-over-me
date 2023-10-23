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

Route::post('/register', 'API\Auth\AuthController@register');
Route::post('/login', 'API\Auth\AuthController@login');
Route::middleware('auth:sanctum')->post('/logout', 'API\Auth\AuthController@logout');
// Route accept penyedia jasa mua
Route::post('/penyedia-jasa-mua/accept', 'API\Register\RegisterMuaController@accept');


// ==================== ROUTE GROUP FOR DASHBOARD PENYEDIA JASA MUA ====================
Route::middleware('auth:sanctum')->group(function () {
    // Route register penyedia jasa mua
    Route::post('/penyedia-jasa-mua/register', 'API\Register\RegisterMuaController@register');
    //Route get profile penyedia jasa mua
    Route::get('/penyedia-jasa-mua/dashboard/profile', 'API\DashboardMuaController@getProfileMua');
});



// ==================== ROUTE GROUP FOR DASHBOARD PENYEDIA JASA MUA ====================
Route::middleware('auth:sanctum')->group(function () {
    // Route register pencari jasa mua
    Route::post('/pencari-jasa-mua/register', 'API\Register\RegisterClientController@register');
});


//Route get kecamatans
Route::get('/kecamatans', 'API\KecamatanController@getKecamatans');
//Route get kategori layanans
Route::get('/kategori-layanans', 'API\KategoriLayananController@getKategoriLayanans');
// Route register Pencari Jasa MUA
Route::middleware('auth:sanctum')->post('/pencari-jasa-mua/register', 'API\PencariJasaMuaController@register');
//Route get profile
Route::middleware('auth:sanctum')->get('/profile', 'API\ProfileController@getProfile');
//Route create layanans
Route::middleware('auth:sanctum')->post('/layanans', 'API\LayananController@create');
