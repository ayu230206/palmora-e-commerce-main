<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProdusenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.produsen', [
            'produsen' => Produsen::all(),
            'produsen_terbaru' => Produsen::orderBy('created_at', 'desc')->first(),
        ]);
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'telp' => 'required|string|max:255',
        ]);

        User::create([
            'username' => $request->nama,
            'password' => Hash::make($request->nama),
            'role' => 'produsen',
        ]);

        Produsen::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'telp' => $request->telp,
            'user' => User::where('username', $request->nama)->first()->id,
        ]);


        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Produsen berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'telp' => 'required|string|max:255',
        ]);

        $produsen = Produsen::findOrFail($id);

        $produsen->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'telp' => $request->telp,
        ]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Pengarang berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = Produsen::findOrFail($id);

        $kategori->delete();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Pengarang berhasil dihapus.',
        ]);
    }
}
