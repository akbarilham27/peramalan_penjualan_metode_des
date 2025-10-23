<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $jumlahProduk = Produk::count();
        $jumlahTransaksi = Transaksi::count();
        $jumlahUser = User::count();

        //Setiap Produk
        $transNeutrikJackNC3FXX = Transaksi::where('id_produk', 'BRGP00020')->sum('jumlah_penjualan');
        $transNeutrikJackNC3MXX = Transaksi::where('id_produk', 'BRGP00021')->sum('jumlah_penjualan');
        $transKreztJackSpeakonTSC = Transaksi::where('id_produk', 'BRGP00682')->sum('jumlah_penjualan');
        $transFocusJackMicMaleGCA = Transaksi::where('id_produk', 'BRGP00843')->sum('jumlah_penjualan');
        $transDAddarioSGAccEJXLite = Transaksi::where('id_produk', 'BRGP00899')->sum('jumlah_penjualan');
        $transDAddarioSGRegulerEXL = Transaksi::where('id_produk', 'BRGP00903')->sum('jumlah_penjualan');
        $transCenturionCLEDXRGBW = Transaksi::where('id_produk', 'BRGP00921')->sum('jumlah_penjualan');
        $transSREXACTJackRCASP116G = Transaksi::where('id_produk', 'BRGP01621')->sum('jumlah_penjualan');
        $transSREXACTJackMaleSVP555V = Transaksi::where('id_produk', 'BRGP01623')->sum('jumlah_penjualan');
        $transSrexactJackAkaiMonoSP102X = Transaksi::where('id_produk', 'BRGP01809')->sum('jumlah_penjualan');
        $transSrexactJackStereoMiniSP110AM = Transaksi::where('id_produk', 'BRGP01811')->sum('jumlah_penjualan');


        // Ambil semua produk untuk dropdown
        $items = Produk::orderBy('nama_produk')->get();

        // Ambil produk yang dipilih dari request
        $produk = $request->id_produk;

        // Data untuk grafik
        $bestValues = [];

        // Jika ada produk yang dipilih
        if ($produk) {
            // Ambil data transaksi untuk produk yang dipilih
            $data = Transaksi::with('dataproduk')
                ->selectRaw('MONTH(tanggal_pengajuan) as bulan, YEAR(tanggal_pengajuan) as tahun, SUM(jumlah_penjualan) as jumlah')
                ->where('id_produk', $produk)
                ->groupBy('bulan', 'tahun')
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get()
                ->toArray();

            // Menyimpan data transaksi yang telah difilter berdasarkan produk
            foreach ($data as $transaksi) {
                $bestValues[] = [
                    'bulan' => $transaksi['bulan'],
                    'tahun' => $transaksi['tahun'],
                    'jumlah' => $transaksi['jumlah']
                ];
            }
        }

        // Kirim data ke view
        return view('dashboard', compact(
            'jumlahProduk',
            'jumlahTransaksi',
            'jumlahUser',
            'items',
            'bestValues',
            'produk',
            'transNeutrikJackNC3FXX',
            'transNeutrikJackNC3MXX',
            'transKreztJackSpeakonTSC',
            'transFocusJackMicMaleGCA',
            'transDAddarioSGAccEJXLite',
            'transDAddarioSGRegulerEXL',
            'transCenturionCLEDXRGBW',
            'transSREXACTJackRCASP116G',
            'transSREXACTJackMaleSVP555V',
            'transSrexactJackAkaiMonoSP102X',
            'transSrexactJackStereoMiniSP110AM',
        ));
    }
}
