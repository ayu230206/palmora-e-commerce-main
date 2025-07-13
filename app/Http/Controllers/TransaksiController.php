<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Produsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role == 'admin') {
            $selectedDay = $request->input('hari');
            $selectedMonth = $request->input('bulan');
            $selectedYear = $request->input('tahun');

            $hariIndonesia = [
                'Senin' => 1,
                'Selasa' => 2,
                'Rabu' => 3,
                'Kamis' => 4,
                'Jumat' => 5,
                'Sabtu' => 6,
                'Minggu' => 7
            ];

            $query = Transaksi::query();

            if ($selectedDay && isset($hariIndonesia[$selectedDay])) {
                $query->whereRaw('DAYOFWEEK(tanggal) = ?', [$hariIndonesia[$selectedDay]]);
            }

            if ($selectedMonth) {
                $query->whereMonth('tanggal', $selectedMonth);
            }

            if ($selectedYear) {
                $query->whereYear('tanggal', $selectedYear);
            }

            $transaksi = $query->orderByDesc('tanggal')->paginate(10);

            return view('dashboard.transaksi', [
                'transaksi' => $transaksi,
                'jumlah_transaksi' => Transaksi::count(),
                'jumlah_transaksi_tahun_ini' => Transaksi::whereYear('tanggal', now()->year)->sum('total'),
                'jumlah_transaksi_bulan_ini' => Transaksi::whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->sum('total'),
                'jumlah_transaksi_hari_ini' => Transaksi::whereDate('tanggal', now()->toDateString())->sum('total'),
                'jumlah_menunggu_persetujuan' => Transaksi::where('validasi', 'menunggu_validasi')->count(),
                'jumlah_transaksi_dikonfirmasi' => Transaksi::where('validasi', 'diterima')->count(),
                'jumlah_transaksi_ditolak' => Transaksi::where('validasi', 'ditolak')->count(),
            ]);
        } elseif (Auth::user()->role == 'produsen') {
            $selectedDay = $request->input('hari');
            $selectedMonth = $request->input('bulan');
            $selectedYear = $request->input('tahun');

            $hariIndonesia = [
                'Senin' => 2,
                'Selasa' => 3,
                'Rabu' => 4,
                'Kamis' => 5,
                'Jumat' => 6,
                'Sabtu' => 7,
                'Minggu' => 1
            ];

            $produsen = Produsen::where('user', Auth::id())->first();
            $produkIds = Produk::where('produsen', $produsen->id)->pluck('id');

            $query = Transaksi::whereIn('produk', $produkIds);

            if ($selectedDay && isset($hariIndonesia[$selectedDay])) {
                $query->whereRaw('DAYOFWEEK(tanggal) = ?', [$hariIndonesia[$selectedDay]]);
            }

            if ($selectedMonth) {
                $query->whereMonth('tanggal', $selectedMonth);
            }

            if ($selectedYear) {
                $query->whereYear('tanggal', $selectedYear);
            }

            $transaksi = $query->orderByDesc('tanggal')->paginate(10);

            return view('dashboard.transaksi', [
                'transaksi' => $transaksi,
                'jumlah_transaksi' => Transaksi::whereIn('produk', $produkIds)->count(),
                'jumlah_transaksi_tahun_ini' => Transaksi::whereIn('produk', $produkIds)
                    ->whereYear('tanggal', now()->year)
                    ->sum('total'),
                'jumlah_transaksi_bulan_ini' => Transaksi::whereIn('produk', $produkIds)
                    ->whereYear('tanggal', now()->year)
                    ->whereMonth('tanggal', now()->month)
                    ->sum('total'),
                'jumlah_transaksi_hari_ini' => Transaksi::whereIn('produk', $produkIds)
                    ->whereDate('tanggal', now()->toDateString())
                    ->sum('total'),
                'jumlah_menunggu_persetujuan' => Transaksi::whereIn('produk', $produkIds)
                    ->where('validasi', 'menunggu_validasi')->count(),
                'jumlah_transaksi_dikonfirmasi' => Transaksi::whereIn('produk', $produkIds)
                    ->where('validasi', 'diterima')->count(),
                'jumlah_transaksi_ditolak' => Transaksi::whereIn('produk', $produkIds)
                    ->where('validasi', 'ditolak')->count(),
            ]);
        }
    }

    public function history_transaksi()
    {
        $customer = Customer::where('user', Auth::id())->first();
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer tidak ditemukan');
        }

        $transaksi = Transaksi::where('customer', $customer->id)
            ->with('produks')
            ->orderByDesc('tanggal')
            ->get();

        return view('home.history', compact('transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::where('user', Auth::id())->first();
            if (!$customer) return response()->json(['success' => false, 'message' => 'Customer tidak ditemukan.'], 400);

            $cartItems = Keranjang::where('customer', $customer->id)->get();
            if ($cartItems->isEmpty()) return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);

            $filePath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

            foreach ($cartItems as $item) {
                Transaksi::create([
                    'customer' => $customer->id,
                    'produk' => $item->produk,
                    'tanggal' => now(),
                    'jumlah' => $item->jumlah,
                    'total' => $item->total,
                    'bukti_transaksi' => $filePath,
                    'validasi' => 'menunggu_validasi',
                ]);
            }

            Keranjang::where('customer', $customer->id)->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaksi gagal: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan transaksi.'], 500);
        }
    }

    public function terima(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $produkId = $transaksi->produk;

        $produk = Produk::select('stok')->find($produkId);

        if ($produk->stok < $transaksi->jumlah) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Stok produk tidak mencukupi.'
            ]);
        }

        $produk->decrement('stok', $transaksi->jumlah);

        $transaksi->validasi = 'diterima';
        $transaksi->save();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Transaksi diterima dan stok telah diperbarui.'
        ]);
    }

    public function tolak(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['validasi' => 'ditolak']);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Transaksi ditolak.'
        ]);
    }
}
