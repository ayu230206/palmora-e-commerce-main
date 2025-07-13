<x-dashboard.main title="Produk">
    <div class="grid sm:grid-cols-2 gap-5 md:gap-6">
        @foreach (['total_produk_terdaftar', 'produk_terbaru'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="
                  {{ $type == 'total_produk_terdaftar' ? 'bg-blue-300' : '' }}
                  {{ $type == 'produk_terbaru' ? 'bg-amber-300' : '' }}
                  p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600 line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 line-clamp-1 capitalize">
                        {{ $type == 'total_produk_terdaftar' ? $produk_terdaftar ?? '-' : '' }}
                        {{ $type == 'produk_terbaru' ? $produk_terbaru->nama_produk ?? 'Belum ada produk Terbaru' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col lg:flex-row gap-5">
        @foreach (['tambah_produk'] as $item)
            <div onclick="{{ $item . '_modal' }}.showModal()"
                class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                <div>
                     <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        {{ $item == 'tambah_produk' ? 'Tambahkan produk untuk mencapai lebih banyak customer' : '' }}
                    </p>
                </div>
                <x-lucide-plus class="{{ $item == 'tambah_produk' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60" />
            </div>
        @endforeach
    </div>

    <div class="flex gap-5">
        @foreach (['manajemen_produk'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Kelola koleksi produk dengan mudah, tambahkan kategori untuk mempermudah pencarian dan
                        pengelolaan
                        data produk.
                    </p>
                </div>
                <div class="w-full px-5 sm:px-7 bg-zinc-50">
                    <input type="text" placeholder="Cari data disini...." name="kategori" id="searchInput"
                        class="input input-sm shadow-md w-full bg-zinc-100">
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    @foreach (['no', 'Nama Produk', 'Produsen', 'Kategori', 'Stok', 'Harga', 'Deskripsi', 'Gambar Produk', ''] as $item)
                                        <th class="uppercase font-bold">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produk as $i => $item)
                                    <tr>
                                        <th class="text-center">{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase text-center">{{ $item->nama_produk }}</td>
                                        <td class="font-semibold uppercase text-center">{{ $item->produsens->nama ?? '-' }}
                                        </td>
                                        <td class="font-semibold uppercase text-center">
                                            {{ $item->kategoris->nama_kategori }}</td>
                                        <td class="font-semibold uppercase text-center"><span
                                                class="px-2 py-1 rounded-lg text-white
                                            @if ($item->stok == 0) bg-red-600
                                            @elseif ($item->stok < 5) bg-yellow-400
                                            @elseif ($item->stok < 10) bg-green-400
                                            @else bg-green-600 @endif">
                                                @if ($item->stok == 0)
                                                    Habis
                                                @else
                                                    {{ $item->stok }}
                                                @endif
                                            </span></td>
                                        <td class="font-semibold uppercase text-center">
                                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="font-semibold uppercase text-center">{{ $item->deskripsi }}</td>
                                        <td class="font-semibold uppercase text-center"><label
                                                for="lihat_modal_{{ $item->id }}"
                                                class="w-full btn btn-warning flex items-center justify-center gap-2 text-white font-bold hover:bg-yellow-600 transition-all duration-200 ease-in-out">
                                                <span>Lihat</span>
                                            </label>

                                            <input type="checkbox" id="lihat_modal_{{ $item->id }}"
                                                class="modal-toggle" />
                                            <div class="modal" role="dialog">
                                                <div class="modal-box max-w-5xl rounded-lg shadow-lg transition-all duration-300 ease-in-out"
                                                    id="modal_box_{{ $item->id }}">
                                                    <div
                                                        class="modal-header flex justify-between items-center p-4 border-b border-gray-600">
                                                        <h3 class="text-xl font-semibold">{{ $item->nama_produk }}</h3>
                                                        <label for="lihat_modal_{{ $item->id }}"
                                                            class="btn btn-sm btn-circle btn-ghost hover:bg-gray-600 transition-all duration-200 ease-in-out">&times;</label>
                                                    </div>
                                                    <div class="modal-body p-6">
                                                        <img src="{{ str_contains($item->gambar, 'https://') ? $item->gambar : asset('storage/' . $item->gambar) }}"
                                                            alt="Image"
                                                            class="w-full h-auto rounded-lg shadow-md mb-6"
                                                            loading="lazy"
                                                            onerror="this.onerror=null; this.src=asset($item -> gambar)">
                                                    </div>
                                                    <h2 class="text-lg font-semibold">Deskripsi</h2>
                                                    <div id="deskripsi_{{ $item->id }}"
                                                        class="mt-4 quill-content text-sm">
                                                        {!! $item->deskripsi !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="flex items-center gap-4">
                                            <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                onclick="document.getElementById('update_modal_{{ $item->id }}').showModal();" />

                                            <dialog id="update_modal_{{ $item->id }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <form method="POST" class="modal-box rounded-xl shadow-lg"
                                                    action="{{ route('update.produk', $item->id) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <h3 class="text-xl font-bold text-gray-800">Update Produk</h3>
                                                    <div class="modal-body mt-5 space-y-4">
                                                        @foreach (['nama_produk', 'produsen', 'kategori', 'stok', 'harga', 'deskripsi', 'gambar'] as $type)
                                                            <div class="flex flex-col gap-2">
                                                                <label for="{{ $type }}"
                                                                    class="text-sm font-medium text-gray-600 capitalize">
                                                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                                                </label>
                                                                @if ($type == 'produsen' && Auth::user()->role == 'admin')
                                                                    <select name="produsen" id="produsen"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300">
                                                                        <option value="">Pilih produsen
                                                                        </option>
                                                                        @foreach ($produsen as $itemprodusen)
                                                                            <option value="{{ $itemprodusen->id }}"
                                                                                class="capitalize"
                                                                                {{ $item->produsen == $itemprodusen->id ? 'selected' : '' }}>
                                                                                {{ $itemprodusen->nama }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    
                                                                @elseif ($type == 'produsen' && Auth::user()->role == 'produsen')
                                                                    <input type="text" id="produsen" name="produsen"
                                                                        value="{{ $item->produsens->id ?? '-' }}"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300 hidden"
                                                                        readonly />
                                                                    <input type="text" id="produsen" name="produsenShow"
                                                                        value="{{ $item->produsens->nama ?? '-' }}"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300"
                                                                        readonly />
                                                                @elseif ($type === 'kategori')
                                                                    <select name="kategori" id="kategori"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300">
                                                                        <option value="">Pilih Kategori</option>
                                                                        @foreach ($kategori as $itemKategori)
                                                                            <option value="{{ $itemKategori->id }}"
                                                                                class="capitalize"
                                                                                {{ $item->kategori_id == $itemKategori->id ? 'selected' : '' }}>
                                                                                {{ $itemKategori->nama_kategori }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                @elseif ($type === 'stok' || $type === 'harga')
                                                                    <input type="number" id="{{ $type }}"
                                                                        name="{{ $type }}"
                                                                        placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                        value="{{ $item->$type }}"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300" />
                                                                @elseif ($type === 'gambar')
                                                                    <input type="file" id="{{ $type }}"
                                                                        name="{{ $type }}"
                                                                        class="file-input file-input-bordered w-full text-black @error($type) file-input-error @enderror" />
                                                                    @if ($item->gambar)
                                                                        <img src="{{ asset('storage/' . $item->gambar) }}"
                                                                            alt="Gambar Produk"
                                                                            class="mt-2 w-32 h-auto rounded">
                                                                    @endif
                                                                @else
                                                                    <input type="text" id="{{ $type }}"
                                                                        name="{{ $type }}"
                                                                        placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                        value="{{ $item->$type }}"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300" />
                                                                @endif
                                                                @error($type)
                                                                    <span
                                                                        class="text-red-500 text-sm">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="modal-action mt-6">
                                                        <button type="button"
                                                            onclick="document.getElementById('update_modal_{{ $item->id }}').close()"
                                                            class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 border-0">
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0"
                                                            onclick="closeAllModals(event)">
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </dialog>

                                            <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                onclick="initDelete('produk', {{ $item }})" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">
                            {{ $produk->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>

<dialog id="tambah_produk_modal" class="modal modal-bottom sm:modal-middle">
    <form method="POST" class="modal-box rounded-xl shadow-lg" action="{{ route('store.produk') }}"
        enctype="multipart/form-data">
        @csrf
        <h3 class="text-xl font-bold text-gray-800">Tambah Produk</h3>
        <div class="modal-body mt-5 space-y-4">
            @foreach (['nama_produk', 'produsen', 'kategori', 'stok', 'harga', 'deskripsi', 'gambar'] as $type)
                <div class="flex flex-col gap-2">
                    <label for="{{ $type }}" class="text-sm font-medium text-gray-600 capitalize">
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </label>

                    @if ($type == 'produsen' && Auth::user()->role == 'admin')
                        <select name="produsen" id="produsen"
                            class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300">
                            <option value="">Pilih produsen</option>
                            @foreach ($produsen as $itemprodusen)
                                <option value="{{ $itemprodusen->id }}" class="capitalize">
                                    {{ $itemprodusen->nama }}
                                </option>
                            @endforeach
                        </select>

                    @elseif ($type == 'produsen' && Auth::user()->role == 'produsen')
                        @php
                            $produsenLogin = \App\Models\Produsen::where('user', Auth::id())->first();
                        @endphp
                        <input type="hidden" id="produsen" name="produsen" value="{{ $produsenLogin->id ?? '' }}" />
                        <input type="text" id="produsenShow" name="produsenShow"
                            value="{{ $produsenLogin->nama ?? 'Data produsen tidak ditemukan' }}"
                            class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300"
                            readonly />

                    @elseif ($type === 'kategori')
                        <select name="kategori" id="kategori"
                            class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300">
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" class="capitalize">{{ $item->nama_kategori }}</option>
                            @endforeach
                        </select>

                    @elseif ($type === 'stok' || $type === 'harga')
                        <input type="number" id="{{ $type }}" name="{{ $type }}"
                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..." value="{{ old($type) }}"
                            class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300" />

                    @elseif ($type === 'gambar')
                        <input type="file" id="{{ $type }}" name="{{ $type }}"
                            class="file-input file-input-bordered w-full text-black @error($type) file-input-error @enderror" />

                    @else
                        <input type="text" id="{{ $type }}" name="{{ $type }}"
                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..." value="{{ old($type) }}"
                            class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300" />
                    @endif

                    @error($type)
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="modal-action mt-6">
            <button type="button" onclick="document.getElementById('tambah_produk_modal').close()"
                class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 border-0">
                Batal
            </button>
            <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0"
                onclick="closeAllModals(event)">
                Simpan
            </button>
        </div>
    </form>
</dialog>
