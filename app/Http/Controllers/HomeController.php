<?php

namespace App\Http\Controllers;

use App\Models\produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Produk::with('produsens');

        if ($request->filled('cari')) {
            $query->where('nama_produk', 'like', '%' . $request->cari . '%');
        }

        $produk = $query->paginate(8);

        return view('home.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function detail($nama_produk)
    {
        $produk = produk::where('nama_produk', $nama_produk)->first();

        if (!$produk) {
            return redirect()->route('index')->with('error', 'Book not found');
        }

        $relatedProduks = Produk::where('kategori', $produk->kategori)
            ->where('id', '!=', $produk->id)
            ->take(4)
            ->get();

        return view('home.detail', [
            'produk' => $produk,
            'relatedProduks' => $relatedProduks
        ]);
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
