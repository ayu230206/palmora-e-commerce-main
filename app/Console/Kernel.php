<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// ⬇️ Import command custom buatanmu
use App\Console\Commands\RemoveDummyProdukSawit;
use App\Console\Commands\RemoveDummyTransaksi;

class Kernel extends ConsoleKernel
{
    // ✅ Daftarkan command di sini agar Artisan bisa mengenali
    protected $commands = [
        RemoveDummyProdukSawit::class,
        RemoveDummyTransaksi::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
