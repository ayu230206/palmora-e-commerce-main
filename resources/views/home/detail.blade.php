<x-main title="Detail Produk {{ $produk->nama_produk }}">
    <div class="max-w-7xl mx-auto p-6 lg:p-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Gambar Produk -->
            <div class="flex items-center justify-center">
                <img src="{{ str_contains($produk->gambar, 'https://') ? $produk->gambar : asset('storage/' . $produk->gambar) }}"
                    alt="{{ $produk->nama_produk }}"
                    class="rounded-2xl shadow-2xl w-full max-h-[500px] object-contain transition duration-300">
            </div>

            <!-- Informasi Produk -->
            <div class="space-y-6">
                <h1 class="text-4xl font-bold text-green-800">{{ $produk->nama_produk }}</h1>

                <div class="text-lg text-gray-700 space-y-1">
                    <p><span class="font-medium text-green-600">Kategori:</span>
                        {{ $produk->kategoris->nama_kategori ?? 'Tidak diketahui' }}</p>
                    <p><span class="font-medium text-green-600">Produsen:</span>
                        {{ $produk->produsens->nama ?? 'Tidak diketahui' }}</p>
                    <p><span class="font-medium text-green-600">Harga:</span> Rp
                        {{ number_format($produk->harga, 0, ',', '.') }}</p>
                </div>

                <div class="text-md text-gray-600 leading-relaxed">
                    <span class="text-green-600 font-semibold">Deskripsi:</span><br>
                    {{ $produk->deskripsi }}
                </div>

                <!-- Tombol -->
                <div class="pt-6">
                    <button id="addToCartButton" data-nama="{{ $produk->nama_produk }}" data-id="{{ $produk->id }}"
                        data-harga="{{ $produk->harga }}"
                        class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium text-base rounded-full shadow-md transition duration-300 ease-in-out whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6h12.4M6 21a1 1 0 100-2 1 1 0 000 2zm12 0a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                        <span id="addToCartText">Tambahkan ke Keranjang</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Produk Terkait -->
        @if ($relatedProduks->isNotEmpty())
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-green-700 mb-6">Produk Terkait</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($relatedProduks as $related)
                        <div
                            class="bg-white border rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                            <img src="{{ str_contains($related->gambar, 'https://') ? $related->gambar : asset('storage/' . $related->gambar) }}"
                                alt="{{ $related->nama_produk }}" class="w-full h-56 object-cover">
                            <div class="p-4 space-y-2">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $related->nama_produk }}</h3>
                                <p class="text-sm text-gray-500">{{ $related->produsens->nama ?? 'Tidak diketahui' }}
                                </p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-green-600 font-bold">Rp
                                        {{ number_format($related->harga, 0, ',', '.') }}</span>
                                    <a href="{{ route('detail_produk', ['nama_produk' => $related->nama_produk]) }}"
                                        class="text-green-600 text-sm hover:underline">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addToCartButton = document.getElementById("addToCartButton");
            const addToCartText = document.getElementById("addToCartText");

            if (localStorage.getItem("cartSuccess")) {
                showToast("Produk berhasil ditambahkan ke keranjang!", "border-green-500");
                localStorage.removeItem("cartSuccess");
            }

            addToCartButton?.addEventListener("click", async function(event) {
                event.preventDefault();

                addToCartButton.disabled = true;
                addToCartText.innerHTML = `<svg class="animate-spin h-5 w-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v8H4z"></path></svg> Menambahkan...`;

                const namaProduk = addToCartButton.dataset.nama;
                const produkId = addToCartButton.dataset.id;
                const harga = parseInt(addToCartButton.dataset.harga);
                const jumlah = 1;
                const total = jumlah * harga;

                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');

                    const customerRes = await fetch("/get-customer-id", {
                        headers: {
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": token
                        }
                    });

                    const customerData = await customerRes.json();
                    const customerId = customerData.customer_id;

                    const addRes = await fetch(`/add-to-cart/${namaProduk}/${produkId}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": token
                        },
                        body: JSON.stringify({
                            customer_id: customerId,
                            produk_id: produkId,
                            jumlah,
                            total
                        })
                    });

                    const result = await addRes.json();

                    if (result.success) {
                        localStorage.setItem("cartSuccess", "true");
                        location.reload();
                    } else {
                        showToast("Gagal menambahkan produk ke keranjang. Silakan login.",
                            "border-red-500");
                    }
                } catch (err) {
                    console.error("Error:", err);
                    showToast("Terjadi kesalahan saat menambahkan ke keranjang.", "border-red-500");
                } finally {
                    addToCartButton.disabled = false;
                    addToCartText.innerHTML = `Tambahkan ke Keranjang`;
                }
            });
        });
    </script>
</x-main>
