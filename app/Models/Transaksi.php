<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi'; 
    protected $foreignKey = 'id_produk';
    protected $keyType = 'string'; 
    protected $fillable = [
        'id_transaksi',
        'id_produk',
        'jumlah_penjualan',
        'tanggal_pengajuan',

    ];
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
    public function dataproduk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}