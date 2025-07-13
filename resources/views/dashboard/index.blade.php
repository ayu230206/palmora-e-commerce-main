<x-dashboard.main title="Dashboard">
    @if (Auth::user()->role === 'admin')
        <div class="p-6 space-y-6">
            <!-- Welcome Section -->
            <div
                class="bg-gradient-to-r from-green-600 to-yellow-500 p-6 rounded-lg shadow-lg flex justify-between items-center text-white">
                <div>
                    <h2 class="text-2xl font-bold">Selamat Datang di PALMORA</h2>
                    <p class="text-sm opacity-80">Kelola Produk, pelanggan, transaksi, dan lainnya dengan mudah.</p>
                </div>
                <x-lucide-book-open class="size-12 opacity-80" />
            </div>

            <!-- Statistik -->
            @php
                $stats = [
                    ['icon' => 'shopping-cart', 'value' => $jumlah_produk, 'label' => 'Total produk'],
                    ['icon' => 'users', 'value' => $jumlah_customer, 'label' => 'Total Customer'],
                    ['icon' => 'shopping-cart', 'value' => $jumlah_transaksi, 'label' => 'Total Transaksi'],
                    [
                        'icon' => 'dollar-sign',
                        'value' => 'Rp' . number_format($jumlah_pendapatan, 0, ',', '.'),
                        'label' => 'Total Pendapatan',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($stats as $stat)
                    <div
                        class="bg-white p-6 rounded-lg shadow-xl flex items-center gap-4 hover:shadow-2xl transition duration-300">
                        @switch($stat['icon'])
                            @case('book')
                                <x-lucide-book class="text-teal-500 size-12" />
                            @break

                            @case('users')
                                <x-lucide-users class="text-indigo-500 size-12" />
                            @break

                            @case('shopping-cart')
                                <x-lucide-shopping-cart class="text-yellow-500 size-12" />
                            @break

                            @case('dollar-sign')
                                <x-lucide-dollar-sign class="text-green-500 size-12" />
                            @break

                            @default
                                <x-lucide-help-circle class="text-gray-500 size-12" />
                        @endswitch

                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $stat['value'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Grafik & Transaksi -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik -->
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-xl">
                    <h3 class="text-lg font-semibold mb-4">Grafik Penjualan</h3>
                    <canvas id="salesChart" class="h-64"></canvas>
                </div>

                <!-- Transaksi Terbaru -->
                <div class="bg-white p-6 rounded-lg shadow-xl">
                    <h3 class="text-lg font-semibold mb-4">Transaksi Terbaru</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse ($transaksi_terbaru as $transaksi)
                            <li class="py-3 flex justify-between items-center">
                                <span class="text-sm">
                                    <span
                                        class="font-semibold">{{ $transaksi->customers->nama ?? 'Seorang pelanggan' }}</span>
                                    baru saja membeli
                                    <span
                                        class="font-semibold">{{ $transaksi->produks->nama_produk ?? 'sebuah produk' }}</span>.
                                </span>
                                <span class="text-sm font-semibold">
                                    Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                                </span>
                            </li>
                        @empty
                            <li class="py-3 text-center text-gray-500">Tidak ada transaksi terbaru</li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    @else
        <div class="p-6 space-y-6">
            <!-- Welcome Section -->
            <div
                class="bg-gradient-to-r from-green-600 to-yellow-500 p-6 rounded-lg shadow-lg flex justify-between items-center text-white">
                <div>
                    <h2 class="text-2xl font-bold">Halo Seller, Selamat Datang!</h2>
                    <p class="text-sm opacity-80">Pantau penjualan dan kelola produk Anda di PALMORA.</p>
                </div>
                <x-lucide-factory class="size-12 opacity-80" />
            </div>

            <!-- Statistik -->
            @php
                $stats = [
                    ['icon' => 'shopping-cart', 'value' => $jumlah_produk, 'label' => 'Produk Anda'],
                    ['icon' => 'shopping-cart', 'value' => $jumlah_transaksi, 'label' => 'Total Transaksi'],
                    [
                        'icon' => 'dollar-sign',
                        'value' => 'Rp' . number_format($jumlah_pendapatan, 0, ',', '.'),
                        'label' => 'Total Pendapatan',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($stats as $stat)
                    <div
                        class="bg-white p-6 rounded-lg shadow-xl flex items-center gap-4 hover:shadow-2xl transition duration-300">
                        @switch($stat['icon'])
                            @case('shopping-cart')
                                <x-lucide-shopping-cart class="text-yellow-500 size-12" />
                            @break

                            @case('dollar-sign')
                                <x-lucide-dollar-sign class="text-green-500 size-12" />
                            @break

                            @default
                                <x-lucide-help-circle class="text-gray-500 size-12" />
                        @endswitch

                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $stat['value'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Grafik & Transaksi -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik -->
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-xl">
                    <h3 class="text-lg font-semibold mb-4">Grafik Penjualan</h3>
                    <canvas id="salesChart" class="h-64"></canvas>
                </div>

                <!-- Transaksi Terbaru -->
                <div class="bg-white p-6 rounded-lg shadow-xl">
                    <h3 class="text-lg font-semibold mb-4">Transaksi Terbaru</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse ($transaksi_terbaru as $transaksi)
                            <li class="py-3 flex justify-between items-center">
                                <span class="text-sm">
                                    <span class="font-semibold">
                                        {{ $transaksi->customers->nama ?? 'Seorang pelanggan' }}
                                    </span> membeli
                                    <span class="font-semibold">
                                        {{ $transaksi->produks->nama_produk ?? 'produk Anda' }}
                                    </span>
                                </span>
                                <span class="text-sm font-semibold">
                                    Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                                </span>
                            </li>
                        @empty
                            <li class="py-3 text-center text-gray-500">Belum ada transaksi baru</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const bulanLabels = @json($bulan_labels);
            const dataPendapatan = @json($data_transaksi);

            const backgroundColors = [
                '#60A5FA', '#34D399', '#FBBF24', '#F87171',
                '#A78BFA', '#F472B6', '#10B981', '#F59E0B',
                '#6366F1', '#EC4899', '#22D3EE', '#FB7185'
            ];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                        label: '',
                        data: dataPendapatan,
                        backgroundColor: backgroundColors.slice(0, bulanLabels.length),
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const label = context.label || '';
                                    const rupiah = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0
                                    }).format(value * 1000);
                                    return `${label}: ${rupiah}`;
                                }

                            },
                            backgroundColor: 'rgba(31, 41, 55, 0.9)',
                            padding: 10
                        }
                    }
                }
            });
        });
    </script>

</x-dashboard.main>
