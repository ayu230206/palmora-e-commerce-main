<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\customer;
use App\Models\Produsen;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // Untuk Admin
            $bulanLabels = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];

            $filteredLabels = [];
            $dataPendapatan = [];

            for ($i = 1; $i <= 12; $i++) {
                $total = DB::table('transaksis')
                    ->whereMonth('tanggal', $i)
                    ->whereYear('tanggal', now()->year)
                    ->sum('total');

                if ($total > 0) {
                    $filteredLabels[] = $bulanLabels[$i - 1];
                    $dataPendapatan[] = round($total / 1000);
                }
            }

            return view('dashboard.index', [
                'jumlah_produk' => DB::table('produks')->count(),
                'jumlah_customer' => DB::table('customers')->count(),
                'jumlah_transaksi' => DB::table('transaksis')->count(),
                'jumlah_pendapatan' => DB::table('transaksis')->sum('total'),
                'transaksi_terbaru' => DB::table('transaksis')->latest()->take(5)->get(),
                'bulan_labels' => $filteredLabels,
                'data_transaksi' => $dataPendapatan,
            ]);
        } else {
            // Untuk Produsen
            $produsen = Produsen::where('user', Auth::id())->firstOrFail();

            $bulanLabels = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];

            $filteredLabels = [];
            $dataPendapatan = [];

            for ($i = 1; $i <= 12; $i++) {
                $total = DB::table('transaksis')
                    ->join('produks', 'transaksis.produk', '=', 'produks.id')
                    ->where('produks.produsen', $produsen->id)
                    ->whereMonth('transaksis.tanggal', $i)
                    ->whereYear('transaksis.tanggal', now()->year)
                    ->sum('transaksis.total');

                if ($total > 0) {
                    $filteredLabels[] = $bulanLabels[$i - 1];
                    $dataPendapatan[] = round($total / 1000);
                }
            }

            return view('dashboard.index', [
                'jumlah_produk' => Produk::where('produsen', $produsen->id)->count(),
                'jumlah_customer' => DB::table('customers')->count(),
                'jumlah_transaksi' => DB::table('transaksis')
                    ->join('produks', 'transaksis.produk', '=', 'produks.id')
                    ->where('produks.produsen', $produsen->id)
                    ->count(),
                'jumlah_pendapatan' => DB::table('transaksis')
                    ->join('produks', 'transaksis.produk', '=', 'produks.id')
                    ->where('produks.produsen', $produsen->id)
                    ->sum('transaksis.total'),
                'transaksi_terbaru' => DB::table('transaksis')
                    ->join('produks', 'transaksis.produk', '=', 'produks.id')
                    ->where('produks.produsen', $produsen->id)
                    ->orderByDesc('transaksis.created_at')
                    ->take(5)
                    ->select('transaksis.*')
                    ->get(),
                'bulan_labels' => $filteredLabels,
                'data_transaksi' => $dataPendapatan,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
