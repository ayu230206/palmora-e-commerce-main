<x-main title="Home" class="w-full">
    <!-- Hero Section -->
    <div class="relative h-[650px] flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/sawitjaya.jpg') }}" alt="Hero Image"
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

    <!-- Product Section -->
    <section id="produk" class="py-24 bg-gradient-to-br from-yellow-50 via-white to-green-50">
        <div class="container mx-auto max-w-7xl px-6">
            <div class="text-center mb-16">
                <h2
                    class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-yellow-500">
                    Produk Olahan Sawit
                </h2>
                <p class="text-lg text-gray-600 mt-4 max-w-xl mx-auto">
                    Temukan berbagai pilihan produk berkualitas dari hasil sawit terbaik Indonesia. Cocok untuk rumah
                    tangga hingga industri.
                </p>
            </div>

            <form action="{{ route('index') }}" method="GET" class="mb-10 max-w-md mx-auto">
                <div class="relative">
                    <input type="text" name="cari" value="{{ request('cari') }}"
                        class="w-full pl-5 pr-12 py-3 rounded-full border border-gray-300 shadow-sm focus:ring-2 focus:ring-green-400 focus:outline-none"
                        placeholder="Cari nama produk...">
                    <button type="submit"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-green-500 hover:text-green-600">
                        <x-lucide-search class="w-5 h-5" />
                    </button>
                </div>
            </form>

            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($produk as $item)
                    <div
                        class="group relative bg-white rounded-3xl shadow-md hover:shadow-2xl transition duration-300 overflow-hidden flex flex-col">

                        <!-- Product Image -->
                        <div class="relative overflow-hidden h-60">
                            <img src="{{ str_contains($item->gambar, 'http') ? $item->gambar : asset('storage/' . $item->gambar) }}"
                                alt="{{ $item->nama_produk }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <!-- Stock Badge -->
                            <span
                                class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold rounded-full {{ $item->stok == 0 ? 'bg-red-500' : 'bg-green-600' }} text-white shadow">
                                {{ $item->stok == 0 ? 'Habis' : 'Tersedia' }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-green-600 transition">
                                    {{ $item->nama_produk }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-2">Produsen: {{ $item->produsens->nama }}</p>

                                <!-- Rating -->
                                <div class="flex items-center text-yellow-400 mb-3">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.562-.954L10 0l2.951 5.956 6.562.954-4.757 4.635 1.122 6.545z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-gray-400 text-xs">(42)</span>
                                </div>

                                <p class="text-2xl font-bold text-green-600 mb-1">Rp
                                    {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>

                            <!-- Action -->
                            <div class="mt-4 flex justify-between items-center">
                                <p class="text-sm {{ $item->stok == 0 ? 'text-red-500' : 'text-gray-500' }}">
                                    Stok: {{ $item->stok == 0 ? 'Habis' : $item->stok }}
                                </p>
                                <a href="{{ route('detail_produk', $item->nama_produk) }}"
                                    class="inline-block px-4 py-2 text-sm font-medium rounded-full bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500 text-white shadow hover:shadow-md transition transform hover:scale-105">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-6">
            {{ $produk->links('vendor.pagination.tailwind') }}
        </div>
    </section>
</x-main>
