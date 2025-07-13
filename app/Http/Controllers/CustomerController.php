<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.customer', [
            'customer_terdaftar' => Customer::count(),
            'customer' => Customer::paginate(10),
            'customer_terbaru' => Customer::orderBy('created_at', 'desc')->first(),
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
        // Validate the incoming request
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'no_telp' => 'required|string|max:20',
        ]);

        // Begin a transaction
        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->nama,
                'password' => Hash::make($request->nama),
                'role' => 'customer',
            ]);

            $customer = Customer::create([
                'user' => $user->id,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
            ]);

            DB::commit();

            return redirect()->back()->with('toast', [
                'type' => 'success',
                'message' => 'Customer berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan, silakan coba lagi.',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:255',
            'email' => 'required|email',
            'no_telp' => 'required|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($id);

            $user = User::findOrFail($customer->user);

            $user->username = $request->nama;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $customer->nama = $request->nama;
            $customer->jenis_kelamin = $request->jenis_kelamin;
            $customer->alamat = $request->alamat;
            $customer->email = $request->email;
            $customer->no_telp = $request->no_telp;
            $customer->save();

            DB::commit();

            return redirect()->back()->with('toast', [
                'type' => 'success',
                'message' => 'Customer berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan, silakan coba lagi.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($id);

            $user = User::findOrFail($customer->user);

            $customer->delete();

            $user->delete();

            DB::commit();

            return redirect()->back()->with('toast', [
                'type' => 'success',
                'message' => 'Customer terkait berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan, silakan coba lagi.',
            ]);
        }
    }
}
