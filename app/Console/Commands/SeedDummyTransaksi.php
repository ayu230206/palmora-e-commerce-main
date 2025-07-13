<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SeedDummyTransaksi extends Command
{
    protected $signature = 'seed:dummy-transaksi';
    protected $description = 'Seeder users (customer), customers, dan transaksis sinkron stok dan nama khas Indonesia';

    public function handle()
    {
        $faker = Faker::create('id_ID');

        $produkList = DB::table('produks')->get();
        if ($produkList->isEmpty()) {
            $this->error("⚠️ Tidak ada produk. Jalankan `seed:produk-sawit` terlebih dahulu.");
            return 1;
        }

        for ($i = 1; $i <= 10; $i++) {
            $userId = DB::table('users')->insertGetId([
                'username' => 'user' . $i,
                'role' => 'customer',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $jenisKelamin = $faker->randomElement(['laki-laki', 'perempuan']);
            $namaDepan = $jenisKelamin === 'laki-laki' ? $faker->firstNameMale() : $faker->firstNameFemale();
            $namaLengkap = $namaDepan . ' ' . $faker->lastName();

            $customerId = DB::table('customers')->insertGetId([
                'user' => $userId,
                'nama' => $namaLengkap,
                'jenis_kelamin' => $jenisKelamin,
                'alamat' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'no_telp' => $faker->phoneNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Max 3 transaksi
            $jumlahTransaksi = rand(1, 3);

            for ($j = 0; $j < $jumlahTransaksi; $j++) {
                $produk = $produkList->random();

                // pastikan stok mencukupi
                if ($produk->stok < 1) continue;

                $jumlah = rand(1, min(5, $produk->stok)); // transaksi tidak boleh melebihi stok

                DB::table('transaksis')->insert([
                    'customer' => $customerId,
                    'produk' => $produk->id,
                    'tanggal' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                    'jumlah' => $jumlah,
                    'total' => $jumlah * $produk->harga,
                    'bukti_transaksi' => null,
                    'validasi' => $faker->randomElement(['menunggu_validasi', 'diterima', 'ditolak']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('produks')->where('id', $produk->id)->decrement('stok', $jumlah);
            }
        }

        $this->info("✅ Berhasil membuat 10 customer dengan transaksi sinkron stok produk.");
        return 0;
    }
}
