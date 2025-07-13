<?php

namespace App\Http\Controllers;

use App\Models\produk;
use App\Models\customer;
use App\Models\keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('index')->with('error', 'Silakan login terlebih dahulu.');
        }

        $id_user = Auth::id();

        $customer = Customer::where('user', $id_user)->first();

        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer not found.');
        }

        // Gabungkan item berdasarkan 'buku' agar tidak muncul berulang
        $cartItems = Keranjang::with(['produks', 'customers'])
            ->where('customer', $customer->id)
            ->get()
            ->groupBy('produk')
            ->map(function ($items) {
                $totalJumlah = $items->sum('jumlah');
                $totalHarga = $items->sum('total');
                $item = $items->first(); // Ambil salah satu data produk
                $item->jumlah = $totalJumlah;
                $item->total = $totalHarga;
                return $item;
            });

        $totalPrice = $cartItems->sum('total');

        return view('home.keranjang', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function getCustomerId()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $customer = customer::where('user', $user->id)->first();
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json(['customer_id' => $customer->id]);
    }

    public function addCart(Request $request, $nama_produk, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $produk = Produk::findOrFail($validated['produk_id']);
            $customer = Customer::findOrFail($validated['customer_id']);

            // Cek apakah buku sudah ada di keranjang customer ini
            $keranjang = Keranjang::where('customer', $validated['customer_id'])
                ->where('produk', $validated['produk_id'])
                ->first();

            if ($keranjang) {
                // Jika sudah ada, update jumlah & total harga (dengan batasan stok)
                if ($keranjang->jumlah + $validated['jumlah'] > $produk->stok) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah melebihi stok yang tersedia!',
                    ], 400);
                }

                $keranjang->jumlah += $validated['jumlah'];
                $keranjang->total = $keranjang->jumlah * $produk->harga;
                $keranjang->save();
            } else {
                // Jika belum ada, tambahkan ke keranjang baru
                if ($validated['jumlah'] > $produk->stok) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah melebihi stok yang tersedia!',
                    ], 400);
                }

                $keranjang = new Keranjang();
                $keranjang->customer = $validated['customer_id'];
                $keranjang->produk = $validated['produk_id'];
                $keranjang->jumlah = $validated['jumlah'];
                $keranjang->total = $keranjang->jumlah * $produk->harga;
                $keranjang->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'data' => $keranjang
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCart(Request $request, $id, $action)
    {
        $cartItem = keranjang::findOrFail($id);
        $produk = $cartItem->produks;

        if ($action == 'increase') {
            if ($cartItem->jumlah < $produk->stok) {
                $cartItem->jumlah += 1;
            } else {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi'], 400);
            }
        } elseif ($action == 'decrease') {
            if ($cartItem->jumlah > 1) {
                $cartItem->jumlah -= 1;
            } else {
                return response()->json(['success' => false, 'message' => 'Jumlah tidak boleh kurang dari 1'], 400);
            }
        }

        $cartItem->total = $cartItem->jumlah * $produk->harga;
        $cartItem->save();

        $totalKeranjang = keranjang::sum('total');

        return response()->json([
            'success' => true,
            'jumlah' => $cartItem->jumlah,
            'total_harga' => number_format($cartItem->total, 0, ',', '.'),
            'total_keranjang' => number_format($totalKeranjang, 0, ',', '.')
        ]);
    }

    public function hapusItem($id)
    {
        try {
            $cartItem = Keranjang::findOrFail($id);
            $cartItem->delete();

            $totalKeranjang = Keranjang::sum('total');

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang',
                'total_keranjang' => 'Rp' . number_format($totalKeranjang, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item, silakan coba lagi'
            ], 500);
        }
    }
}
