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


// ==================== ROUTE GROUP FOR DASHBOARD PENYEDIA JASA MUA ====================
Route::middleware('auth:sanctum')->group(function () {
    // Route register penyedia jasa mua
    Route::post('/penyedia-jasa-mua/register', 'API\Register\RegisterMuaController@register');
    // Route dashboard penyedia jasa mua
    Route::get('/penyedia-jasa-mua/dashboard/profile', 'API\Dashboard\DashboardMuaController@getProfileMua');
    // Route layanan MUA
    Route::get('/penyedia-jasa-mua/dashboard/layananmua', 'API\Dashboard\DashboardMuaController@getLayananMua');
    // Route Pemesanan terbaru
    Route::get('/penyedia-jasa-mua/dashboard/pesananterbaru', 'API\Dashboard\DashboardMuaController@getPemesananTerbaru');
    // Route Seluruh Pemesanan
    Route::get('/penyedia-jasa-mua/dashboard/seluruhpesanan', 'API\Dashboard\DashboardMuaController@getSeluruhPemesanan');
    // Route Ulasan
    Route::get('/penyedia-jasa-mua/dashboard/ulasan', 'API\Dashboard\DashboardMuaController@getUlasan');

    // Route detail pemesanan
    Route::get('/penyedia-jasa-mua/pemesanan/detailpemesanan/{id}', 'API\Pemesanan\PemesananController@getDetailPemesanan');
    // Route tolak dan terima pemesanan
    Route::get('/penyedia-jasa-mua/pemesanan/acceptpemesanan/{id}', 'API\Pemesanan\PemesananController@acceptPemesanan');
    Route::get('/penyedia-jasa-mua/pemesanan/declinepemesanan/{id}', 'API\Pemesanan\PemesananController@declinePemesanan');

    // Route Manajemen Katalog
    Route::get('/penyedia-jasa-mua/katalog/previewmua', 'API\Manajemenkatalog\ManajemenKatalogController@getPreviewMua');
    Route::post('/penyedia-jasa-mua/katalog/createpreviewmua', 'API\Manajemenkatalog\ManajemenKatalogController@createPreviewMua');
    Route::post('/penyedia-jasa-mua/katalog/deletepreviewmua/{id}', 'API\Manajemenkatalog\ManajemenKatalogController@deletePreviewMua');
    // Route Katalog Jasa, update katalog jasa, tambah katalog jasa, get katalog jasa
    Route::get('/penyedia-jasa-mua/katalog/katalogjasa', 'API\Manajemenkatalog\ManajemenKatalogController@getKatalogJasa');
    Route::get('/penyedia-jasa-mua/katalog/previewkatalogjasa/{id}', 'API\Manajemenkatalog\ManajemenKatalogController@getPreviewKatalog');
    Route::post('/penyedia-jasa-mua/katalog/updatekatalogjasa', 'API\Manajemenkatalog\ManajemenKatalogController@updateKatalogJasa');
    Route::get('/penyedia-jasa-mua/katalog/kategorilayanan', 'API\Manajemenkatalog\ManajemenKatalogController@getKategoriLayanan');
    Route::post('/penyedia-jasa-mua/katalog/createkatalogjasa', 'API\Manajemenkatalog\ManajemenKatalogController@createKatalogJasa');
    Route::post('/penyedia-jasa-mua/katalog/deletekatalogjasa/{id}', 'API\Manajemenkatalog\ManajemenKatalogController@deleteKatalogJasa');
    
    // Route Seluruh Pesanan dan Detil Pesanan
    Route::get('/penyedia-jasa-mua/ulasan/seluruhulasan', 'API\Ulasan\UlasanController@getSeluruhUlasan');
    Route::get('/penyedia-jasa-mua/ulasan/detailulasan/{id}', 'API\Ulasan\UlasanController@getDetailUlasan');
});


// ==================== ROUTE GROUP FOR DASHBOARD PENCARI JASA MUA ====================
Route::middleware('auth:sanctum')->group(function () {
    // Route register pencari jasa mua
    Route::post('/pencari-jasa-mua/register', 'API\Register\RegisterClientController@register');
    // Route dashboard pencari jasa mua
    Route::get('/pencari-jasa-mua/dashboard/{limit?}', 'API\Dashboard\DashboardClientController@index');
    // Route get all layanan mua
    Route::get('/pencari-jasa-mua/layanan-mua', 'API\Layanan\LayananController@getAllLayananMua');
    //route search mua
    Route::post('/pencari-jasa-mua/search-mua', 'API\Dashboard\DashboardClientController@searchMua');
    // route detail mua
    Route::get('/pencari-jasa-mua/detail-mua/{id}', 'API\Dashboard\DetailJasaMuaController@index');
    // route layanan
    Route::get('/pencari-jasa-mua/layananMua/{id}', 'API\Layanan\LayananController@getLayananMua');
    // route cek pemesanan
    Route::post('/pencari-jasa-mua/cek-pemesanan', 'API\Pemesanan\PemesananController@cekPemesanan');
    // route autofill pemesanann
    Route::get('/pencari-jasa-mua/autofill-pemesanan', 'API\Profile\ProfileClientController@getProfileClient');
    // route create pemesanan
    Route::post('/pencari-jasa-mua/create-pemesanan', 'API\Pemesanan\PemesananController@createPemesanan');
    // route get pemesanan done
    Route::get('/pencari-jasa-mua/pemesanan-done', 'API\Pemesanan\PemesananController@getPemesananDone');
    // route create review
    Route::post('/pencari-jasa-mua/create-review', 'API\Review\ReviewController@createReview');
    // route get review
    Route::get('/pencari-jasa-mua/get-review/{id}', 'API\Review\ReviewController@getReview');
});


//Route get kecamatans
Route::get('/kecamatans', 'API\KecamatanController@getKecamatans');
Route::get('/wilayah-kecamatans', 'API\KecamatanController@getWilayah');


//Route get kategori layanans
Route::get('/kategori-layanans', 'API\KategoriLayananController@getKategoriLayanans');
//Route get profile
Route::middleware('auth:sanctum')->get('/profile', 'API\ProfileController@getProfile');
//Route create layanans
Route::middleware('auth:sanctum')->post('/layanans', 'API\LayananController@create');

// Route accept penyedia jasa mua
Route::post('/penyedia-jasa-mua/accept', 'API\Register\RegisterMuaController@accept');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');