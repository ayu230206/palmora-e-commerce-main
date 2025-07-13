<?php

namespace App\Http\Controllers;

use App\Models\produk;
use App\Models\kategori;
use App\Models\produsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return view('dashboard.produk', [
                'produk' => Produk::orderBy('stok', 'asc')->paginate(10),
                'produk_terbaru' => Produk::orderBy('created_at', 'desc')->first(),
                'produsen' => Produsen::all(),
                'kategori' => kategori::all(),
                'produk_terdaftar' => Produk::count(),
            ]);
        } else {
            $produsen = Produsen::where('user', Auth::user()->id)->first();

            $produsenId = null;
            $produsenNama = null;

            if ($produsen) {
                $produsenId = $produsen->id;
                $produsenNama = $produsen->nama;
            }

            return view('dashboard.produk', [
                'produk' => Produk::orderBy('stok', 'asc')->where('produsen', $produsen->id)->paginate(10),
                'produk_terbaru' => Produk::orderBy('created_at', 'desc')->where('produsen', $produsen->id)->first(),
                'produsen' => $produsen,
                'kategori' => kategori::all(),
                'produk_terdaftar' => Produk::where('produsen', $produsen->id)->count(),
                'produsenId' => $produsenId,
                'produsenNama' => $produsenNama,
            ]);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'produsen' => 'required|integer|exists:produsens,id',
            'kategori' => 'required|integer|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nama_produk', 'kategori', 'produsen', 'stok', 'harga', 'deskripsi']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Produk berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'produsen' => 'required|integer|exists:produsens,id',
            'kategori' => 'required|integer|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $produk = Produk::findOrFail($id);
        $data = $request->only(['nama_produk', 'kategori', 'produsen', 'stok', 'harga', 'deskripsi']);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Produk berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $kategori = Produk::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Produk berhasil dihapus.',
        ]);
    }
}
