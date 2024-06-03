<?php

namespace App\Exports;

use App\Transaksi;
use App\Kategori;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView
{
    public function view(): View
    {
        // Data kategori
        $kategori = Kategori::all();

        // Data filter
        $dari = request()->input('dari');
        $sampai = request()->input('sampai');
        $id_kategori = request()->input('kategori');

        // Periksa kategori yang dipilih
        if ($id_kategori == "semua") {
            // Jika semua, tampilkan semua transaksi
            $laporan = Transaksi::whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        } else {
            // Jika yang dipilih bukan semua, tampilkan transaksi berdasarkan kategori yang dipilih
            $laporan = Transaksi::where('kategori_id', $id_kategori)
                ->whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        }

        // Passing data laporan ke view laporan_hasil
        return view('laporan_hasil', [
            'laporan' => $laporan,
            'kategori' => $kategori,
            'dari' => $dari,
            'sampai' => $sampai,
            'id_kategori' => $id_kategori,
        ]);
    }
}
