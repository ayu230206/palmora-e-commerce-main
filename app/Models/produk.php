<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    use HasFactory;

    protected $fillable = ['nama_produk', 'kategori', 'produsen', 'stok', 'harga', 'deskripsi', 'gambar'];

    public function produsens()
    {
        return $this->belongsTo(Produsen::class, 'produsen');
    }

    public function kategoris()
    {
        return $this->belongsTo(Kategori::class, 'kategori');
    }
}
