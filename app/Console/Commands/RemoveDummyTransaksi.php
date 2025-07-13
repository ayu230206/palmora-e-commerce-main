<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDummyTransaksi extends Command
{
    protected $signature = 'remove:dummy-transaksi';
    protected $description = 'Hapus dummy data customer dan transaksinya';

    public function handle()
    {
        $userIds = DB::table('users')
            ->where('role', 'customer')
            ->where('username', 'like', 'user%')
            ->pluck('id');

        $customerIds = DB::table('customers')
            ->whereIn('user', $userIds)
            ->pluck('id');

        DB::table('transaksis')->whereIn('customer', $customerIds)->delete();
        DB::table('customers')->whereIn('id', $customerIds)->delete();
        DB::table('users')->whereIn('id', $userIds)->delete();

        $this->info("✅ Dummy transaksi dan customer berhasil dihapus.");
    }
}
