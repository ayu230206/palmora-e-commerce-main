<x-dashboard.main title="Transaksi">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach ([
        'jumlah_transaksi_tahun_ini' => ['icon' => 'calendar', 'color' => 'bg-white'],
        'jumlah_transaksi_bulan_ini' => ['icon' => 'calendar-range', 'color' => 'bg-white'],
        // 'jumlah_transaksi_hari_ini' => ['icon' => 'calendar-days', 'color' => 'bg-white'],
    ] as $item => $config)
            <div
                class="{{ $config['color'] }} flex flex-col sm:flex-row items-start sm:items-center gap-5 p-5 sm:p-7 bg-white border-back rounded-xl w-full transition">
                <div class="text-5xl">
                    @if ($config['icon'] == 'calendar')
                        <x-lucide-calendar class="w-12 h-12" />
                    @elseif ($config['icon'] == 'calendar-range')
                        <x-lucide-calendar-range class="w-12 h-12" />
                    @endif
                </div>
                <div>
                    <h1 class="text-lg font-semibold capitalize">{{ str_replace('_', ' ', $item) }}</h1>
                    <h1 class="text-3xl sm:text-4xl font-bold">Rp {{ number_format($$item, 0, ',', '.') }}</h1>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col xl:flex-row gap-5">
        @foreach (['jumlah_transaksi', 'jumlah_menunggu_persetujuan', 'jumlah_transaksi_dikonfirmasi', 'jumlah_transaksi_ditolak'] as $item)
            <div
                class="
                  flex flex-col sm:flex-row items-start sm:items-center gap-5
                  p-5 sm:p-7 bg-white border-back rounded-xl w-full transition">
                <div>
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        @if ($item == 'jumlah_transaksi')
                            Total semua transaksi yang telah masuk ke sistem.
                        @elseif ($item == 'jumlah_menunggu_persetujuan')
                            Total transaksi yang masih menunggu persetujuan admin.
                        @elseif ($item == 'jumlah_transaksi_dikonfirmasi')
                            Total transaksi yang telah divalidasi dan disetujui.
                        @elseif ($item == 'jumlah_transaksi_ditolak')
                            Total transaksi yang ditolak atau tidak valid.
                        @endif
                    </p>
                </div>
                <h1 class="{{ $item == 'jumlah_transaksi' ? '' : 'hidden' }} text-3xl sm:text-4xl font-semibold">
                    {{ $jumlah_transaksi }}
                </h1>
                <h1
                    class="{{ $item == 'jumlah_menunggu_persetujuan' ? '' : 'hidden' }} text-3xl sm:text-4xl font-semibold">
                    {{ $jumlah_menunggu_persetujuan }}
                </h1>
                <h1
                    class="{{ $item == 'jumlah_transaksi_dikonfirmasi' ? '' : 'hidden' }} text-3xl sm:text-4xl font-semibold">
                    {{ $jumlah_transaksi_dikonfirmasi }}
                </h1>
                <h1
                    class="{{ $item == 'jumlah_transaksi_ditolak' ? '' : 'hidden' }} text-3xl sm:text-4xl font-semibold">
                    {{ $jumlah_transaksi_ditolak }}
                </h1>
            </div>
        @endforeach
    </div>

    <div class="flex gap-5">
        @foreach (['manajemen_transaksi'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Kelola semua transaksi dengan mudah, termasuk persetujuan, pembatalan, dan validasi pembayaran.
                    </p>
                </div>
                <div class="w-full px-5 sm:px-7 bg-zinc-50 flex flex-wrap gap-3 items-center">
                    <input type="text" placeholder="Cari data disini..." name="pengarang" id="searchInput"
                        class="input input-sm shadow-md w-full sm:w-auto flex-1 bg-zinc-100" />

                    <select id="filterHari" class="select select-sm shadow-md bg-zinc-100">
                        <option value="">Semua Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>

                    <select id="filterBulan" class="select select-sm shadow-md bg-zinc-100">
                        <option value="">Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>

                    <select id="filterTahun" class="select select-sm shadow-md bg-zinc-100">
                        <option value="">Semua Tahun</option>
                        @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>

                    <button class="btn btn-sm btn-primary shadow-md" onclick="applyFilters()">Filter</button>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    @foreach (['no', 'Nama Customer', 'Item', 'Tanggal', 'Jumlah', 'Total', 'Bukti Transaksi', 'validasi', 'opsi'] as $item)
                                        <th class="uppercase font-bold">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $i => $item)
                                    <tr>
                                        <th class="text-center">{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase text-center">{{ $item->customers->nama }}
                                        </td>
                                        <td class="font-semibold uppercase text-center">
                                            {{ $item->produks->nama_produk }}
                                        </td>
                                        <td class="font-semibold uppercase text-center">{{ $item->tanggal }}</td>
                                        <td class="font-semibold uppercase text-center">{{ $item->jumlah }}</td>
                                        <td class="font-semibold uppercase text-center">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}
                                        </td>
                                        <td class="font-semibold uppercase text-center"><label
                                                for="lihat_modal_{{ $item->id }}"
                                                class="w-full btn btn-warning flex items-center justify-center gap-2 text-white font-bold hover:bg-yellow-600 transition-all duration-200 ease-in-out">
                                                <span>Lihat</span>
                                            </label>

                                            <input type="checkbox" id="lihat_modal_{{ $item->id }}"
                                                class="modal-toggle" />
                                            <div class="modal modal-bottom sm:modal-middle">
                                                <div class="modal-box max-w-4xl rounded-lg shadow-lg transition-all duration-300 ease-in-out"
                                                    id="modal_box_{{ $item->id }}">
                                                    <!-- Header Modal -->
                                                    <div class="flex justify-between items-center border-b pb-3">
                                                        <h2 class="text-lg font-semibold">Bukti Transaksi</h2>
                                                        <label for="lihat_modal_{{ $item->id }}"
                                                            class="btn btn-sm btn-circle btn-ghost hover:bg-gray-200 transition-all duration-200 ease-in-out">
                                                            ✕
                                                        </label>
                                                    </div>

                                                    <!-- Konten Modal -->
                                                    <div class="p-6 flex justify-center">
                                                        @php
                                                            $filePath = str_contains($item->bukti_transaksi, 'https://')
                                                                ? $item->bukti_transaksi
                                                                : asset('storage/' . $item->bukti_transaksi);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        @endphp

                                                        @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                            <img src="{{ $filePath }}" alt="Bukti Transaksi"
                                                                class="w-full max-h-[600px] object-contain rounded-lg shadow-md"
                                                                loading="lazy"
                                                                onerror="this.onerror=null; this.src='{{ asset('default-image.jpg') }}';">
                                                        @elseif ($fileExtension === 'pdf')
                                                            <iframe src="{{ $filePath }}"
                                                                class="w-full h-[600px] border rounded-lg shadow-md"
                                                                frameborder="0">
                                                            </iframe>
                                                        @else
                                                            <p class="text-center text-red-500 font-semibold">Format
                                                                file tidak didukung.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-semibold uppercase text-center">
                                            @if ($item->validasi == 'diterima')
                                                <span class="badge badge-success">Diterima</span>
                                            @elseif($item->validasi == 'menunggu_validasi')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @else
                                                <span class="badge badge-error">Ditolak</span>
                                            @endif
                                        </td>
                                        @if ($item->validasi === 'menunggu_validasi')
                                            <td class="flex items-center gap-2 justify-center">
                                                <button
                                                    onclick="document.getElementById('terima_modal_{{ $item->id }}').showModal();"
                                                    class="btn btn-success text-white hover:bg-emerald-600 transition-all duration-300 ease-in-out py-2 px-4 rounded-md shadow-lg transform hover:scale-105">
                                                    <x-lucide-check class="w-4 h-4 mr-2" /> Terima
                                                </button>
                                                <span>|</span>
                                                <button
                                                    onclick="document.getElementById('tolak_modal_{{ $item->id }}').showModal();"
                                                    class="btn btn-outline btn-error hover:bg-red-600 text-red-500 hover:text-white transition-all duration-300 ease-in-out py-2 px-4 rounded-md shadow-lg transform hover:scale-105">
                                                    <x-lucide-x class="w-4 h-4 mr-2" /> Tolak
                                                </button>
                                            </td>
                                        @elseif ($item->validasi === 'ditolak')
                                            <td class="flex text-center gap-4 justify-center">
                                                <x-lucide-x
                                                    class="stroke-red-500 w-5 h-5 transition-all duration-300 ease-in-out" />
                                                <span class="text-sm text-red-500">Ditolak</span>
                                            </td>
                                        @elseif ($item->validasi === 'diterima')
                                            <td class="flex text-center gap-4 justify-center">
                                                <x-lucide-check
                                                    class="stroke-emerald-500 w-5 h-5 transition-all duration-300 ease-in-out" />
                                                <span class="text-sm text-emerald-500">Diterima</span>
                                            </td>
                                        @else
                                            <td class="flex items-center gap-4 justify-center text-gray-500">Tidak
                                                Dikenal</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">
                            {{ $transaksi->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>

@foreach ($transaksi as $i => $item)
    @foreach (['terima', 'tolak'] as $action)
        <dialog id="{{ $action }}_modal_{{ $item->id }}" class="modal modal-bottom sm:modal-middle">
            <form action="{{ route($action . '.' . 'transaksi', ['id' => $item->id]) }}" method="POST"
                class="modal-box rounded-lg shadow-lg bg-white p-8">
                @csrf
                @method('PUT')

                <!-- Modal Header -->
                <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ ucfirst($action) }} Transaksi</h3>

                <!-- Modal Body -->
                <div class="modal-body mb-6 text-gray-600">
                    <div class="mb-4">
                        <p class="text-base">
                            Anda sedang {{ $action }} transaksi untuk customer
                            <strong>{{ $item->customers->nama }}</strong> -
                            <strong>{{ $item->produks->nama_produk }}</strong> pada tanggal
                            <strong>{{ $item->tanggal }}</strong>.
                        </p>
                        <p class="text-sm mt-2">
                            Apakah Anda yakin ingin melanjutkan?
                        </p>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="modal-action flex justify-end gap-4">
                    <button type="button"
                        onclick="document.getElementById('{{ $action }}_modal_{{ $item->id }}').close();"
                        class="btn btn-ghost text-gray-500 hover:text-gray-700 focus:outline-none transition duration-200">Tutup</button>
                    <button type="submit"
                        class="btn btn-primary capitalize bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-all duration-300"
                        onclick="closeAllModals(event)">
                        {{ ucfirst($action) }}
                    </button>
                </div>
            </form>
        </dialog>
    @endforeach
@endforeach

<script>
    function applyFilters() {
        var hari = document.getElementById('filterHari').value;
        var bulan = document.getElementById('filterBulan').value;
        var tahun = document.getElementById('filterTahun').value;

        window.location.href = "?hari=" + hari + "&bulan=" + bulan + "&tahun=" + tahun;
    }
</script>
