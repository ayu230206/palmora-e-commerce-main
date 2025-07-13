<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    use HasFactory;

    protected $fillable = ['customer', 'produk', 'tanggal', 'jumlah', 'total', 'bukti_transaksi', 'validasi'];

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer');
    }

    public function produks()
    {
        return $this->belongsTo(Produk::class, 'produk');
    }
}
