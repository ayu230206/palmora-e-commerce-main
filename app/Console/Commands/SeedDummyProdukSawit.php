<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SeedDummyProdukSawit extends Command
{
    protected $signature = 'seed:produk-sawit';
    protected $description = 'Seeder produk sawit dengan gambar lokal tersimpan di storage';

    public function handle()
    {
        $faker = Faker::create('id_ID');

        $kategoriSawit = [
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
        ];

        $kategoriIds = [];
        foreach ($kategoriSawit as $kategori) {
            $kategoriIds[] = DB::table('kategoris')->insertGetId([
                'nama_kategori' => $kategori,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $produsenIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $userId = DB::table('users')->insertGetId([
                'username' => 'produsen' . $i,
                'role' => 'produsen',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $produsenIds[] = DB::table('produsens')->insertGetId([
                'user' => $userId,
                'nama' => 'CV ' . $faker->lastName . ' ' . $faker->companySuffix,
                'alamat' => $faker->address,
                'email' => $faker->unique()->companyEmail,
                'telp' => $faker->phoneNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 20; $i++) {
            $kategoriIndex = array_rand($kategoriIds);
            $kategoriId = $kategoriIds[$kategoriIndex];
            $kategoriNama = $kategoriSawit[$kategoriIndex];

            $imageUrl = 'https://loremflickr.com/640/640/palm-oil,' . urlencode($kategoriNama);

            DB::table('produks')->insert([
                'nama_produk' => ucfirst($faker->words(2, true)) . ' ' . $kategoriNama,
                'kategori' => $kategoriId,
                'produsen' => $faker->randomElement($produsenIds),
                'stok' => $faker->numberBetween(10, 300),
                'harga' => $faker->numberBetween(20, 150) * 1000,
                'deskripsi' => $faker->realText(100),
                'gambar' => $imageUrl,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info("✅ Produk sawit berhasil ditambahkan.");
        return 0;
    }
}
