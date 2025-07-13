<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDummyProdukSawit extends Command
{
    /**
     * Nama perintah artisan yang bisa dijalankan di terminal
     */
    protected $signature = 'remove:produk-sawit';

    /**
     * Deskripsi singkat perintah artisan
     */
    protected $description = 'Menghapus data dummy produk sawit, produsen, dan kategori hasil seeder produk-sawit';

    /**
     * Fungsi utama saat perintah dijalankan
     */
    public function handle()
    {
        // Hapus semua produk yang gambarnya dari loremflickr (ciri produk dummy)
        DB::table('produks')->where('gambar', 'like', '%loremflickr.com%')->delete();

        // Ambil semua user ID yang role-nya 'produsen' dan usernamenya mulai dari 'produsen'
        $userIds = DB::table('users')
            ->where('role', 'produsen')
            ->where('username', 'like', 'produsen%')
            ->pluck('id');

        // Hapus data di tabel produsens yang terkait user dummy
        DB::table('produsens')->whereIn('user', $userIds)->delete();

        // Hapus user dummy produsen dari tabel users
        DB::table('users')->whereIn('id', $userIds)->delete();

        // Hapus kategori dummy sawit
        DB::table('kategoris')->whereIn('nama_kategori', [
            'Minyak Goreng',
            'Margarin',
            'Sabun Sawit',
            'Kosmetik Sawit',
            'Lilin Sawit',
            'Biodiesel',
            'Gliserol',
            'Minyak Industri',
            'Pelumas Nabati',
            'Detergen'
        ])->delete();

        // Informasi berhasil
        $this->info("✅ Semua data dummy produk sawit berhasil dihapus.");
    }
}
