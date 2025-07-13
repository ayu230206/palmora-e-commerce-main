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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer');
            $table->unsignedBigInteger('produk');
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->integer('total');
            $table->foreign('customer')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('produk')->references('id')->on('produks')->onDelete('cascade')->onUpdate('cascade');
            $table->string('bukti_transaksi')->nullable();
            $table->enum('validasi', ['diterima', 'menunggu_validasi', 'ditolak'])->default('menunggu_validasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
