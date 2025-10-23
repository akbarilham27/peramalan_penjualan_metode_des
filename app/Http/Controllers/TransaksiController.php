<?php

namespace App\Http\Controllers;

use App\Models\transaksi;
use App\Models\produk;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\TransaksiImport;

class TransaksiController extends Controller
{
    public function transaksi()
    {
        $data_transaksi = Transaksi::with('produk')->get();
        return view('transaksi.transaksi', compact('data_transaksi'));
    }

    public function tambahtransaksi()
    {
        $data_produk = produk::all()->sortBy('nama_produk');
        $data_transaksi = transaksi::all();
        $lastTransaction = Transaksi::orderBy('id_transaksi', 'desc')->first();
        $newIdTransaksi = $lastTransaction ? $lastTransaction->id_transaksi + 1 : 1; // Menambahkan 1 untuk ID baru
        return view('transaksi.tambahtransaksi', compact('data_produk', 'data_transaksi', 'newIdTransaksi'));
    }
    public function inserttransaksi(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_transaksi' => 'required|array',
            'id_produk' => 'required|array',
            'jumlah_penjualan' => 'required|array',
            'tanggal_pengajuan' => 'required|array',
        ]);
    
        // Simpan data dalam bentuk array
        $data = [];
        foreach ($request->id_transaksi as $key => $value) {
            $data[] = [
                'id_transaksi' => $value,
                'id_produk' => $request->id_produk[$key],
                'jumlah_penjualan' => $request->jumlah_penjualan[$key],
                'tanggal_pengajuan' => $request->tanggal_pengajuan[$key],
            ];
        }
    
        // Insert data ke database
        transaksi::insert($data);
    
        return redirect()->route('transaksi')->with('success', 'Data Berhasil Ditambah');
    }

    public function tampilkantransaksi($id_transaksi)
    {
        $data_produk = Produk::all();
        $data_transaksi = Transaksi::where('id_transaksi', $id_transaksi)->first();
        return view('transaksi.tampiltransaksi', compact('data_transaksi', 'data_produk'));
    }
    public function updatetransaksi(Request $request, $id_transaksi)
    {
        $data_transaksi = Transaksi::where('id_transaksi', $id_transaksi)->first();
        $data_transaksi->update($request->all());
        return redirect()->route('transaksi')->with('success', 'Data Berhasil Di Update');
    }
    public function deletetransaksi(Request $request, $id_transaksi)
    {
        $data_transaksi = Transaksi::where('id_transaksi', $id_transaksi)->first();
        $data_transaksi->delete();
        return redirect()->route('transaksi')->with('success', 'Data Berhasil Di Delete');
    }

    public function importtransaksiexel(Request $request)
    {
        $data_transaksi = $request->file('file');
        $namafile = $data_transaksi->getClientOriginalName();
        $data_transaksi->move('TransaksiImport', $namafile);
        Excel::import(new TransaksiImport, \public_path('/TransaksiImport/' . $namafile));
        return \redirect()->back();
    }

    public function deletesemuatransaksi()
    {
        Transaksi::truncate();
        return redirect()->back();
    }
}
