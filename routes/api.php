<?php

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/barang', function () {
    $barang = Barang::orderBy('id_barang', 'desc')->get()->map(function ($item) {
        return [
            'id_barang' => $item->id_barang,
            'nama' => $item->nama,
            'kategori' => $item->kategori,
            'supplier' => $item->supplier,
            'stok' => $item->stok,
            'harga' => $item->harga,
            'gambar' => $item->gambar ? asset('storage/' . $item->gambar) : null,
        ];
    });

    return response()->json([
        'status' => 'success',
        'data' => $barang
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
