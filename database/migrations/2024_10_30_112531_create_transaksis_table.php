<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi'); // AUTO_INCREMENT primary key
            $table->string('id_produk'); // Hapus AUTO_INCREMENT di sini
            $table->integer('jumlah_penjualan');
            $table->string('tanggal_pengajuan'); // Lebih baik gunakan DATE atau DATETIME jika memungkinkan
            $table->timestamps();

            // Tambahkan foreign key jika diperlukan
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};
