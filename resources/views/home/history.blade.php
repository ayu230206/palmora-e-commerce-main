<x-main title="History" class="w-full">
    <div class="relative h-[650px] flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/hero.jpg') }}" alt="Hero Image"
            class="absolute inset-0 w-full h-full object-cover z-0 transition duration-1000 transform hover:scale-105">
        <div class="absolute inset-0 bg-black bg-opacity-50 z-10"></div>
        <div class="relative z-20 text-center text-white px-6">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight drop-shadow-lg">
                Produk Olahan Sawit Unggulan <br> dari Indonesia
            </h1>
            <a href="#produk"
                class="inline-block px-8 py-3 text-lg font-semibold rounded-full bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500 shadow-lg transition transform hover:scale-110">
                Jelajahi Produk
            </a>
        </div>
    </div>

    <div class="container mx-auto my-10 p-6 bg-white shadow-xl rounded-xl">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Daftar Transaksi</h2>

        <div class="overflow-x-auto">
            <table class="table w-full border-collapse">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="p-3 text-left">#</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Nama Produk</th>
                        <th class="p-3 text-center">Jumlah</th>
                        <th class="p-3 text-center">Total</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $key => $item)
                        <tr class="hover:bg-gray-100 transition">
                            <td class="p-3">{{ $key + 1 }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="p-3">{{ $item->produks->nama_produk ?? 'Tidak diketahui' }}</td>
                            <td class="p-3 text-center">{{ $item->jumlah }}</td>
                            <td class="p-3 text-center">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                @if ($item->validasi == 'diterima')
                                    <span class="badge badge-success">Diterima</span>
                                @elseif($item->validasi == 'menunggu_validasi')
                                    <span class="badge badge-warning">Menunggu</span>
                                @else
                                    <span class="badge badge-error">Ditolak</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if ($item->bukti_transaksi)
                                    <a href="{{ asset('storage/' . $item->bukti_transaksi) }}" target="_blank"
                                        class="btn btn-sm btn-outline btn-primary">Lihat</a>
                                @else
                                    <span class="text-gray-400">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-main>
