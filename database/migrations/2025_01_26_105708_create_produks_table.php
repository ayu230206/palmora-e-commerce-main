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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->unsignedBigInteger('kategori');
            $table->unsignedBigInteger('produsen');
            $table->integer('stok')->default(0);
            $table->integer('harga')->default(0);
            $table->text('deskripsi');
            $table->string('gambar')->nullable();

            $table->foreign('kategori')
                ->references('id')->on('kategoris')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('produsen')
                ->references('id')->on('produsens')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
