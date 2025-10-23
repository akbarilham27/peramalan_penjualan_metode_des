<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produks';
    protected $primaryKey = 'id_produk';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_produk',
        'nama_produk',
        'jenis_produk',
        'harga_produk',
    ];
}
