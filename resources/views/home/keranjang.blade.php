<x-main title="Keranjang" class="w-full">
    <script>
        let cartItems = @json($cartItems);
    </script>

    <div class="relative h-[650px] flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/pasarpikul.jpg') }}" alt="Hero Image"
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

    <div class="container mx-auto p-8">
        <div class="bg-base-100 shadow-2xl rounded-3xl p-8">
            <h2 class="text-4xl font-extrabold text-primary mb-8">Keranjang Belanja</h2>

            <div class="overflow-x-auto">
                <table class="table w-full border-collapse">
                    <thead>
                        <tr class="bg-neutral text-neutral-content">
                            @foreach (['Produk', 'Jumlah', 'Harga', 'Total', ''] as $item)
                                <th class="p-5 uppercase font-bold text-center">{{ $item }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $cartItem)
                            <tr id="row-{{ $cartItem->id }}" class="border-b hover:bg-base-200">
                                <td class="p-5 flex items-center gap-5">
                                    <img src="{{ str_contains($cartItem->produks->gambar, 'https://') ? $cartItem->produks->gambar : asset('storage/' . $cartItem->produks->gambar) }}"
                                        alt="{{ $cartItem->produks->judul }}" class="w-20 h-20 rounded-xl shadow-lg" />
                                    <div>
                                        <span
                                            class="block font-semibold text-xl">{{ $cartItem->produks->nama_produk }}</span>
                                    </div>
                                </td>
                                <td class="p-5 text-center">
                                    <div class="flex justify-center items-center gap-3">
                                        <button class="btn btn-sm btn-outline btn-error"
                                            onclick="updateQuantity({{ $cartItem->id }}, 'decrease')">-</button>
                                        <span id="quantity-{{ $cartItem->id }}"
                                            class="text-lg font-semibold">{{ $cartItem->jumlah }}</span>
                                        <button class="btn btn-sm btn-outline btn-primary"
                                            onclick="updateQuantity({{ $cartItem->id }}, 'increase')">+</button>
                                    </div>
                                </td>
                                <td class="p-5 text-center text-lg font-medium">
                                    Rp<span
                                        id="price-{{ $cartItem->id }}">{{ number_format($cartItem->produks->harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="p-5 text-center text-lg font-bold">
                                    Rp<span
                                        id="total-{{ $cartItem->id }}">{{ number_format($cartItem->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="p-5 text-center">
                                    <button onclick="hapusItem({{ $cartItem->id }})"
                                        class="btn btn-sm btn-error">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center mt-8">
                <div class="text-2xl font-bold">Total: <span id="total-price"
                        class="text-primary">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span></div>
                <button class="btn btn-lg btn-success mt-4 md:mt-0 px-8" onclick="openPembayaranModal(cartItems)">
                    Checkout
                </button>
            </div>
        </div>
    </div>

    <dialog id="pembayaran_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box rounded-2xl shadow-2xl bg-white p-6 sm:p-8 max-w-lg mx-auto">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <x-lucide-dollar-sign class="w-8 h-8" />
                Pembayaran
            </h3>
            <p class="text-gray-600 mb-4">Unggah bukti pembayaran Anda. Pastikan gambar jelas dan sesuai.</p>

            <!-- Tabel Produk -->
            <div class="overflow-x-auto mb-4">
                <table class="table w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="p-3 text-center">Produk</th>
                            <th class="p-3 text-center">Jumlah</th>
                            <th class="p-3 text-center">Harga</th>
                            <th class="p-3 text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items-list" class="text-gray-800">
                        <!-- Cart items will be injected here dynamically -->
                    </tbody>
                </table>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="space-y-4">
                <div>
                    <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700">Unggah Bukti
                        Pembayaran</label>
                    <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*, .pdf"
                        class="file-input file-input-bordered file-input-primary w-full mt-2 rounded-lg border-gray-300 shadow-sm focus:ring-primary focus:border-primary transition duration-200">
                </div>
            </div>

            <!-- Total Harga -->
            <div class="mt-4 text-lg font-semibold text-gray-800 flex justify-between items-center">
                <span>Total Pembayaran:</span>
                <span id="total-harga-pembayaran" class="text-xl text-green-600 font-bold">Rp0</span>
            </div>

            <!-- Tombol Aksi -->
            <div class="modal-action mt-6 flex justify-end gap-4">
                <button type="button" onclick="document.getElementById('pembayaran_modal').close()"
                    class="btn px-6 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 transition rounded-lg shadow">
                    Batal
                </button>
                <button
                    class="btn px-6 py-2 bg-green-500 text-white font-medium hover:bg-green-600 transition rounded-lg shadow"
                    onclick="submitPayment()">
                    Kirim Bukti
                </button>
            </div>
        </div>
    </dialog>

    <dialog id="hapus_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box rounded-xl shadow-lg">
            <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
            <p class="mt-2 text-gray-600">Apakah Anda yakin ingin menghapus item ini?</p>
            <div class="modal-action mt-4">
                <button type="button" onclick="document.getElementById('hapus_modal').close()"
                    class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 border-0">
                    Batal
                </button>
                <button id="confirm-delete-btn" class="btn btn-primary bg-red-600 hover:bg-red-700 text-white border-0">
                    Hapus
                </button>
            </div>
        </div>
    </dialog>
    <script>
        function openPembayaranModal(cartItems) {
            console.log("Data cartItems:", cartItems); // Debugging, lihat data di console browser

            // Pastikan cartItems adalah objek yang valid
            if (!cartItems || Object.keys(cartItems).length === 0) {
                console.warn("CartItems kosong atau tidak valid");
                showToast('Keranjang belanja kosong!', 'red-500');
                return;
            }

            const cartItemsList = document.getElementById('cart-items-list');
            if (!cartItemsList) {
                console.error("Elemen cart-items-list tidak ditemukan");
                return;
            }

            cartItemsList.innerHTML = ''; // Kosongkan daftar sebelumnya
            let totalHarga = 0;

            Object.values(cartItems).forEach(item => {
                if (!item || !item.produks) return; // Pastikan item memiliki data buku

                const judulBuku = item.produks.nama_produk || 'Tidak diketahui';
                const harga = item.produks.harga || 0;
                const jumlah = item.jumlah || 0;
                const total = item.total || 0;

                const row = document.createElement('tr');
                row.innerHTML = `
            <td class="p-3 text-center">${judulBuku}</td>
            <td class="p-3 text-center">${jumlah}</td>
            <td class="p-3 text-center">Rp${harga.toLocaleString()}</td>
            <td class="p-3 text-center">Rp${total.toLocaleString()}</td>
        `;
                cartItemsList.appendChild(row);

                totalHarga += total;
            });

            const totalHargaElement = document.getElementById('total-harga-pembayaran');
            if (totalHargaElement) {
                totalHargaElement.innerText = `Rp${totalHarga.toLocaleString()}`;
            } else {
                console.error("Elemen total-harga-pembayaran tidak ditemukan");
            }

            const modal = document.getElementById('pembayaran_modal');
            if (modal) {
                modal.showModal();
            } else {
                console.error("Elemen pembayaran_modal tidak ditemukan");
            }
        }

        function submitPayment() {
            const fileInput = document.getElementById('bukti_pembayaran');
            const file = fileInput.files[0];

            if (!file) {
                showToast('Silakan unggah bukti pembayaran terlebih dahulu.', 'red-500');
                return;
            }

            const formData = new FormData();
            formData.append('bukti_pembayaran', file);

            fetch("{{ route('submit-pembayaran') }}", {
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengunggah bukti pembayaran.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast('Bukti pembayaran berhasil dikirim!', 'green-500');
                        document.getElementById('pembayaran_modal').close();
                        fileInput.value = '';
                    } else {
                        showToast(data.message || 'Terjadi kesalahan.', 'red-500');
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showToast('Terjadi kesalahan. Silakan coba lagi.', 'red-500');
                });
        }
    </script>
    <script>
        function updateQuantity(cartItemId, action) {
            fetch("{{ url('/update-keranjang') }}/" + cartItemId + "/" + action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Perbarui jumlah item di tampilan keranjang
                        const quantityElement = document.getElementById(`quantity-${cartItemId}`);
                        const totalElement = document.getElementById(`total-${cartItemId}`);
                        const totalPriceElement = document.getElementById('total-price');

                        if (quantityElement) quantityElement.innerText = data.jumlah;
                        if (totalElement) totalElement.innerText = `Rp${data.total_harga.toLocaleString()}`;
                        if (totalPriceElement) totalPriceElement.innerText =
                            `Rp${data.total_keranjang.toLocaleString()}`;

                        // Perbarui data `cartItems` untuk modal pembayaran
                        if (cartItems[cartItemId]) {
                            cartItems[cartItemId].jumlah = data.jumlah;
                            cartItems[cartItemId].total = data.total_harga;
                        }

                        // Jika modal pembayaran sedang terbuka, update isinya
                        if (document.getElementById('pembayaran_modal').open) {
                            openPembayaranModal(cartItems);
                        }

                        showToast('Jumlah berhasil diperbarui!', 'green-500');
                    } else {
                        showToast(data.message, 'red-500');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan!', 'red-500');
                });
        }
    </script>

    <script>
        let deleteCartItemId = null;

        function hapusItem(cartItemId) {
            deleteCartItemId = cartItemId;
            document.getElementById('hapus_modal').showModal();
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (deleteCartItemId) {
                fetch("{{ url('/hapus-item-keranjang') }}/" + deleteCartItemId, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`row-${deleteCartItemId}`)
                                .remove(); // Hapus baris dari tabel
                            document.getElementById('total-price').innerText = data
                                .total_keranjang; // Update total harga
                            showToast('Item berhasil dihapus!', 'yellow-500'); // Toast sukses
                        } else {
                            showToast(data.message, 'red-500'); // Toast error
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        showToast('Terjadi kesalahan saat menghapus!', 'red-500'); // Toast error
                    })
                    .finally(() => {
                        document.getElementById('hapus_modal').close();
                        deleteCartItemId = null;
                    });
            }
        });
    </script>


</x-main>
