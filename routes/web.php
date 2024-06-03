<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('halo', function () {
    return "Halo, Selamat datang di tutorial laravel 
    www.malasngoding.com";
});

Route::get('blog', function () {
    return view('blog');
});

Route::get('dosen', 'DosenController@index');

Auth::routes([
    'register' => false,
    'reset' => false
]);

// Route Navigation
Route::get('/home', 'HomeController@index')->name('home');

// Route CRUD
Route::get('/kategori', 'HomeController@kategori');
Route::get('/kategori/tambah', 'HomeController@kategori_tambah');
Route::post('/kategori/aksi', 'HomeController@kategori_aksi');
Route::get('/kategori/edit/{id}', 'HomeController@kategori_edit');
Route::put('/kategori/update/{id}', 'HomeController@kategori_update');
Route::get('/kategori/hapus/{id}', 'HomeController@kategori_hapus');

// Route Transaksi
Route::get('/transaksi', 'HomeController@transaksi');
Route::get('/transaksi/tambah', 'HomeController@transaksi_tambah');
Route::post('/transaksi/aksi', 'HomeController@transaksi_aksi');
Route::get('/transaksi/edit/{id}', 'HomeController@transaksi_edit');
Route::put(
    '/transaksi/update/{id}',
    'HomeController@transaksi_update'
);
Route::get('/transaksi/hapus/{id}', 'HomeController@transaksi_hapus');
// pencarian
Route::get('/transaksi/cari', 'HomeController@transaksi_cari');

// Laporan
Route::get('/laporan', 'HomeController@laporan');
Route::get('/laporan/hasil', 'HomeController@laporan_hasil');

// Print Excel Laporan
Route::get('/laporan/print', 'HomeController@laporan_print');
Route::get('/laporan/excel', 'HomeController@laporan_excel');

// ganti password
Route::get('/ganti_password', 'HomeController@ganti_password');
Route::post('/ganti_password/aksi', 'HomeController@ganti_password_aksi');


//kuliah
// Route::get('dosen', 'DosenController@index');
// Route::get('/pegawai/{nama}', 'PegawaiController@index');
// Route::get('/formulir', 'PegawaiController@formulir');
// Route::post('/formulir/proses', 'PegawaiController@proses');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
