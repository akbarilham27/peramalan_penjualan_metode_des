<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilPE;
use App\Models\Produk;
use App\Models\Transaksi;
use Hamcrest\Type\IsNumeric;

class PeramalanController
{
    public function index(Request $request)
    {
        $items = Produk::orderBy('nama_produk')->get();
        $alphaValues = [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9];
        $minMae = PHP_INT_MAX;
        $bestAlpha = [];
        $bestValues = [];
        $produk = $request->id_produk;
        $bulanRamalan = $request->bulan_ramalan;
        $mae = 0;

        if ($produk) {
            foreach ($alphaValues as $alpha) {
                $currentValues = $this->calculate($produk, $alpha, $bulanRamalan);
                $currentMae = $this->calculateMae($currentValues['values']);

                if ($currentMae < $minMae) {
                    $minMae = $currentMae;
                    $bestAlpha = $alpha;
                    $bestValues = $currentValues['values'];
                    $mae = $currentMae;
                }
            }
            $this->saveForecastValues($produk, $bestValues, $bestAlpha);
        }

        return view('Peramalan.peramalan', compact('items', 'alphaValues', 'bestValues', 'bestAlpha', 'mae'));
    }

    public function calculate(string $id_produk, float $alpha, string $bulanRamalan)
    {

        $data = Transaksi::with('dataproduk')
            ->selectRaw('MONTH(tanggal_pengajuan) as bulan, YEAR(tanggal_pengajuan) as tahun, SUM(jumlah_penjualan) as jumlah')
            ->where('id_produk', $id_produk)
            ->groupBy('bulan', 'tahun')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get()
            ->toArray();

        $values = [];
        $stSebelumnya = 0;
        $sstSebelumnya = 0;
        $at = 0;
        $bt = 0;
        $prevAt = 0;
        $prevBt = 0;

        if (empty($data)) {
            return [
                'values' => [
                    [
                        'bulan' => 0,
                        'tahun' => 0,
                        'jumlah' => 0,
                        'st' => 0,
                        'sst' => 0,
                        'at' => 0,
                        'bt' => 0,
                        'forecast' => 0,
                    ]
                ]
            ];
        }

        foreach ($data as $key => $transaksi) {
            $jumlah = $transaksi['jumlah'];

            if ($key === 0) {
                $st = $jumlah;
                $sst = $jumlah;
            } else {
                $st = $alpha * $jumlah + (1 - $alpha) * $stSebelumnya;
                $sst = $alpha * $st + (1 - $alpha) * $sstSebelumnya;
            }

            $at = (2 * $st) - $sst;
            $bt = ($alpha / (1 - $alpha)) * ($st - $sst);

            if ($key == 0) {
                $forecast = 0;
            } else {
                $forecast = round(max(0, $prevAt + $prevBt));
            }

            $values[] = [
                'bulan' => $transaksi['bulan'],
                'tahun' => $transaksi['tahun'],
                'jumlah' => $jumlah,
                'st' => $st,
                'sst' => $sst,
                'at' => $at,
                'bt' => $bt,
                'forecast' => $forecast,
            ];

            $prevAt = $at;
            $prevBt = $bt;
            $stSebelumnya = $st;
            $sstSebelumnya = $sst;
        }


        for ($i = 0; $i < $bulanRamalan; $i++) {
            $nextSt = $alpha * 0 + (1 - $alpha) * $stSebelumnya;
            $nextSst = $alpha * $nextSt + (1 - $alpha) * $sstSebelumnya;
            $nextAt = (2 * $nextSt) - $nextSst;
            $nextBt = ($alpha / (1 - $alpha)) * ($nextSt - $nextSst);
            $nextForecast = round(max(0, $prevAt + $prevBt));

            $bulanTerakhir = end($values)['bulan'];
            $tahunTerakhir = end($values)['tahun'];

            if ($bulanTerakhir == 12) {
                $bulanTerbaru = 1;
                $tahunTerbaru = $tahunTerakhir + 1;
            } else {
                $bulanTerbaru = $bulanTerakhir + 1;
                $tahunTerbaru = $tahunTerakhir;
            }
            $values[] = [
                'bulan' => $bulanTerbaru,
                'tahun' => $tahunTerbaru,
                'jumlah' => 0,
                'st' => $nextSt,
                'sst' => $nextSst,
                'at' => $nextAt,
                'bt' => $nextBt,
                'forecast' => $nextForecast,
            ];

            $stSebelumnya = $nextSt;
            $sstSebelumnya = $nextSst;
            $prevAt = $nextAt;
            $prevBt = $nextBt;
        }
        return [
            'values' => $values,
        ];
    }

    public function calculateMae($values)
    {
        $totalError = $maeCount = 0;

        foreach ($values as $value) {
            $jumlah = $value['jumlah'];
            $forecast = $value['forecast'];
            $totalError += abs($jumlah - $forecast);
            $maeCount++;
        }
        return $totalError / $maeCount;
    }


    public function saveForecastValues($produk, $values, $alpha)
    {
        foreach ($values as $value) {
            HasilPE::create([
                'produk_id' => $produk,
                'bulan' => $value['bulan'],
                'tahun' => $value['tahun'],
                'jumlah' => $value['jumlah'],
                'st' => $value['st'],
                'sst' => $value['sst'],
                'at' => $value['at'],
                'bt' => $value['bt'],
                'forecast' => $value['forecast'],
                'alpha' => $alpha,
            ]);
        }
    }
}
