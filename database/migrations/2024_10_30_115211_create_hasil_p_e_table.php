<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hasil_p_e', function (Blueprint $table) {
            $table->id();
            $table->string('produk_id');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->float('jumlah');
            $table->float('st');
            $table->float('sst');
            $table->float('at');
            $table->float('bt');
            $table->float('forecast');
            $table->float('pe')->nullable();
            $table->float('alpha');
            $table->timestamps();

             $table->foreign('produk_id')->references('id_produk')->on('produks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_p_e');
    }
};
