<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kategori;
use App\Transaksi;

use App\User;

use Hash;
use Auth;

// Laporan Excel dan Juga Print
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tanggal_hari_ini = date('Y-m-d');
        $bulan_ini = date('m');
        $tahun_ini = date('Y');
        $pemasukan_hari_ini = Transaksi::where('jenis', 'Pemasukan')
            ->whereDate('tanggal', $tanggal_hari_ini)
            ->sum('nominal');
        $pemasukan_bulan_ini = Transaksi::where('jenis', 'Pemasukan')
            ->whereMonth('tanggal', $bulan_ini)
            ->sum('nominal');
        $pemasukan_tahun_ini = Transaksi::where('jenis', 'Pemasukan')
            ->whereYear('tanggal', $tahun_ini)
            ->sum('nominal');
        $seluruh_pemasukan = Transaksi::where('jenis', 'Pemasukan')->sum('nominal');
        $pengeluaran_hari_ini = Transaksi::where('jenis', 'Pengeluaran')
            ->whereDate('tanggal', $tanggal_hari_ini)
            ->sum('nominal');
        $pengeluaran_bulan_ini = Transaksi::where('jenis', 'Pengeluaran')
            ->whereMonth('tanggal', $bulan_ini)
            ->sum('nominal');
        $pengeluaran_tahun_ini = Transaksi::where('jenis', 'Pengeluaran')
            ->whereYear('tanggal', $tahun_ini)
            ->sum('nominal');
        $seluruh_pengeluaran = Transaksi::where('jenis', 'Pengeluaran')->sum('nominal');
        return view(
            'home',
            [
                'pemasukan_hari_ini' => $pemasukan_hari_ini,
                'pemasukan_bulan_ini' => $pemasukan_bulan_ini,
                'pemasukan_tahun_ini' => $pemasukan_tahun_ini,
                'seluruh_pemasukan' => $seluruh_pemasukan,
                'pengeluaran_hari_ini' => $pengeluaran_hari_ini,
                'pengeluaran_bulan_ini' => $pengeluaran_bulan_ini,
                'pengeluaran_tahun_ini' => $pengeluaran_tahun_ini,
                'seluruh_pengeluaran' => $seluruh_pengeluaran
            ]
        );
    }

    public function kategori()
    {
        $kategori = Kategori::all();
        return view('kategori', ['kategori' => $kategori]);
    }

    public function kategori_tambah()
    {
        return view('kategori_tambah');
    }

    public function kategori_aksi(Request $data)
    {
        $data->validate([
            'kategori' => 'required'
        ]);
        $kategori = $data->kategori;
        Kategori::insert([
            'kategori' => $kategori
        ]);
        return redirect('kategori')->with("sukses", "Kategori berhasil 
tersimpan");
    }

    public function kategori_edit($id)
    {
        $kategori = Kategori::find($id);
        return view('kategori_edit', ['kategori' => $kategori]);
    }

    public function kategori_update($id, Request $data)
    {
        // form validasi
        $data->validate([
            'kategori' => 'required'
        ]);
        $nama_kategori = $data->kategori;
        // update kategori
        $kategori = Kategori::find($id);
        $kategori->kategori = $nama_kategori;
        $kategori->save();
        // alihkan halaman ke halaman kategori
        return redirect('kategori')->with("sukses", "Kategori berhasil diubah");
    }

    public function kategori_hapus($id)
    {
        // $kategori = Kategori::find($id);
        // $kategori->delete();
        // return redirect('kategori')->with("sukses", "Kategori berhasil dihapus");
        // hapus kategori berdasarkan id yang dipilih
        $kategori = Kategori::find($id);
        $kategori->delete();
        // menghapus transaksi berdasarkan id kategori yang dihapus
        $transaksi = Transaksi::where('kategori_id', $id);
        $transaksi->delete();
        return redirect('kategori')->with("sukses", "Kategori berhasil dihapus");
    }

    // Method Transaksi
    public function transaksi()
    {
        // mengambil data transaksi
        // $transaksi = Transaksi::all();
        // passing data transaksi ke view transaksi.blade.php
        // return view('transaksi', ['transaksi' => $transaksi]);

        // mengurutkan data transaksi berdasarkan id terbesar (transaksi terbaru)
        // dan menampilkannya dalam bentuk pagination
        $transaksi = Transaksi::orderBy('id', 'desc')->paginate(6);
        // passing data transaksi ke view transaksi.blade.php
        return view('transaksi', ['transaksi' => $transaksi]);
    }


    public function transaksi_tambah()
    {
        // mengambil data kategori
        $kategori = Kategori::all();
        // passing data kategori ke view transaksi_tambah.blade.php
        return view('transaksi_tambah', ['kategori' => $kategori]);
    }

    public function transaksi_aksi(Request $data)
    {
        // validasi tanggal,jenis,kategori,nominal wajib isi
        $data->validate([
            'tanggal' => 'required',
            'jenis' => 'required',
            'kategori' => 'required',
            'nominal' => 'required'
        ]);
        // insert data ke table transaksi
        Transaksi::insert([
            'tanggal' => $data->tanggal,
            'jenis' => $data->jenis,
            'kategori_id' => $data->kategori,
            'nominal' => $data->nominal,
            'keterangan' => $data->keterangan
        ]);
        // alihkan halaman ke halaman transaksi sambil mengirim session pesan notifikasi
        return redirect('transaksi')->with("sukses", "Transaksi berhasil 
   tersimpan");
    }

    public function transaksi_edit($id)
    {
        // mengambil data kategori
        $kategori = Kategori::all();
        // mengambil data transaksi berdasarkan id
        $transaksi = Transaksi::find($id);
        // passing data kategori dan transaksi ke view transaksi_edit.blade.php
        return view('transaksi_edit', ['kategori' => $kategori, 'transaksi' =>
        $transaksi]);
    }

    public function transaksi_update($id, Request $data)
    {
        // validasi tanggal,jenis,kategori,nominal wajib isi
        $data->validate([
            'tanggal' => 'required',
            'jenis' => 'required',
            'kategori' => 'required',
            'nominal' => 'required'
        ]);
        // ambil transaksi berdasarkan id
        $transaksi = Transaksi::find($id);
        // ubah data tanggal, jenis, kategori, nominal, keterangan
        $transaksi->tanggal = $data->tanggal;
        $transaksi->jenis = $data->jenis;
        $transaksi->kategori_id = $data->kategori;
        $transaksi->nominal = $data->nominal;
        $transaksi->keterangan = $data->keterangan;
        // Simpan perubahan
        $transaksi->save();
        // alihkan halaman ke halaman transaksi sambil mengirim session pesan notifikasi
        return redirect('transaksi')->with("sukses", "Transaksi berhasil diubah");
    }

    public function transaksi_hapus($id)
    {
        // Ambil data transaksi berdasarkan id, kemudian hapus
        $transaksi = Transaksi::find($id);
        $transaksi->delete();
        // Alihkan halaman kembali ke halaman transaksi sambil mengirim pesan notifikasi
        return redirect('transaksi')->with("sukses", "Transaksi berhasil 
dihapus");
    }

    public function transaksi_cari(Request $data)
    {
        // keyword pencarian
        $cari = $data->cari;
        // mengambil data transaksi
        $transaksi = Transaksi::orderBy('id', 'desc')
            ->where('jenis', 'like', "%" . $cari . "%")
            ->orWhere('tanggal', 'like', "%" . $cari . "%")
            ->orWhere('keterangan', 'like', "%" . $cari . "%")
            ->orWhere('nominal', '=', "%" . $cari . "%")
            ->paginate(6);
        // menambahkan keyword pencarian ke data transaksi
        $transaksi->appends($data->only('cari'));
        // passing data transaksi ke view transaksi.blade.php
        return view('transaksi', ['transaksi' => $transaksi]);
    }

    public function laporan()
    {
        // data kategori
        $kategori = Kategori::all();
        // passing data kategori ke view laporan
        return view('laporan', ['kategori' => $kategori]);
    }

    public function laporan_hasil(Request $request)
    {
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required'
        ]);

        // Data kategori
        $kategori = Kategori::all();

        // Data filter
        $dari = $request->dari;
        $sampai = $request->sampai;
        $id_kategori = $request->kategori;

        // Periksa kategori yang dipilih
        if ($id_kategori == "semua") {
            // Jika semua, tampilkan semua transaksi
            $laporan = Transaksi::whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        } else {
            // Jika yang dipilih bukan "semua", 
            // tampilkan transaksi berdasarkan kategori yang dipilih
            $laporan = Transaksi::where('kategori_id', $id_kategori)
                ->whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        }
        // Passing data laporan ke view laporan_hasil
        return view('laporan_hasil', [
            'dari' => $dari,
            'sampai' => $sampai,
            'laporan' => $laporan,
            'kategori' => $kategori,
            'id_kategori' => $id_kategori
        ]);
    }

    public function laporan_print(Request $request)
    {
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required'
        ]);

        // Data kategori
        $kategori = Kategori::all();

        // Data filter
        $dari = $request->dari;
        $sampai = $request->sampai;
        $id_kategori = $request->kategori;

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
        // Passing data laporan ke view laporan_print
        return view('laporan_print', ['laporan' => $laporan, 'kategori' =>
        $kategori]);
    }

    public function laporan_excel()
    {
        return Excel::download(new LaporanExport, 'laporan.xlsx');
    }

    public function ganti_password()
    {
        return view('gantipassword');
    }


    public function ganti_password_aksi(Request $request)
    {
        // Periksa apakah inputan "password sekarang (current password)" sesuai dengan password sekarang
        if (!Hash::check($request->input('current-password'), Auth::user()->password)) {
            // Jika tidak sesuai, alihkan halaman kembali ke form ganti password
            // sambil mengirimkan pemberitahuan bahwa password tidak sesuai
            return redirect()->back()->with("error", "Password sekarang tidak sesuai.");
        }

        // Periksa apakah password baru sama dengan password yang sekarang
        if ($request->input('current-password') == $request->input('new-password')) {
            // Jika password baru yang diinput sama dengan password lama (password sekarang)
            // alihkan kembali ke form ganti password sambil mengirim pemberitahuan
            return redirect()->back()->with("error", "Password baru tidak boleh sama dengan password sekarang.");
        }

        // Validasi input
        $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        // Ganti password user/pengguna yang sedang login dengan password baru
        $user = Auth::user();
        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        // Kembalikan halaman dan kirim pemberitahuan ganti password sukses
        return redirect()->back()->with("success", "Password berhasil diganti !");
    }
}
