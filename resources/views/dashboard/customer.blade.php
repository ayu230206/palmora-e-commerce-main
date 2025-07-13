<x-dashboard.main title="Customer">
    <div class="grid sm:grid-cols-2 gap-5 md:gap-6">
        @foreach (['total_customer_terdaftar', 'customer_terbaru'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="
                  {{ $type == 'total_customer_terdaftar' ? 'bg-blue-300' : '' }}
                  {{ $type == 'customer_terbaru' ? 'bg-amber-300' : '' }}
                  p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600 line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 line-clamp-1">
                        {{ $type == 'total_customer_terdaftar' ? $customer_terdaftar ?? '-' : '' }}
                        {{ $type == 'customer_terbaru' ? $customer_terbaru->nama ?? 'Belum ada customer Terbaru' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col lg:flex-row gap-5">
        @foreach (['tambah_customer'] as $item)
            <div onclick="{{ $item . '_modal' }}.showModal()"
                class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                <div>
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        {{ $item == 'tambah_customer' ? 'Menambahkan customer untuk transaksi penjualan pada toko' : '' }}
                    </p>
                </div>
                <x-lucide-plus class="{{ $item == 'tambah_customer' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60" />
            </div>
        @endforeach
    </div>

    <div class="flex gap-5">
        @foreach (['manajemen_customer_toko'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Kelola lebih mudah dengan manajemen customer.
                    </p>
                </div>
                <div class="w-full px-5 sm:px-7 bg-zinc-50">
                    <input type="text" placeholder="Cari data disini...." name="customer" id="searchInput"
                        class="input input-sm shadow-md w-full bg-zinc-100">
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    @foreach (['no', 'Nama customer', 'Jenis Kelamin', 'Alamat', 'Email', 'No Telp', 'register at', 'updated at', ''] as $item)
                                        <th class="uppercase font-bold">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer as $i => $item)
                                    <tr>
                                        <th class="text-center">{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase text-center">{{ $item->nama }}</td>
                                        <td class="font-semibold uppercase text-center">
                                            {{ $item->jenis_kelamin ?? '-' }}
                                        <td class="font-semibold uppercase text-center">{{ $item->alamat ?? '-' }}
                                        <td class="font-semibold uppercase text-center">{{ $item->email ?? '-' }}
                                        <td class="font-semibold uppercase text-center">{{ $item->no_telp ?? '-' }}
                                        <td class="font-semibold uppercase text-center">{{ $item->created_at ?? '-' }}
                                        <td class="font-semibold uppercase text-center">{{ $item->updated_at ?? '-' }}
                                        </td>
                                        <td class="flex items-center gap-4">
                                            <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                onclick="document.getElementById('update_modal_{{ $item->id }}').showModal();" />

                                            <dialog id="update_modal_{{ $item->id }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <form method="POST" class="modal-box rounded-xl shadow-lg"
                                                    action="{{ route('update.customer', $item->id) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <h3 class="text-xl font-bold text-gray-800">Update Customer</h3>
                                                    <div class="modal-body mt-5 space-y-4">
                                                        @foreach (['nama', 'jenis_kelamin', 'alamat', 'email', 'no_telp'] as $type)
                                                            <div class="flex flex-col gap-2">
                                                                <label for="{{ $type }}_{{ $item->id }}"
                                                                    class="text-sm font-medium text-gray-600 capitalize">
                                                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                                                </label>
                                                                @if ($type == 'jenis_kelamin')
                                                                    <select name="jenis_kelamin"
                                                                        id="jenis_kelamin_{{ $item->id }}"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300">
                                                                        <option value="">Pilih Jenis Kelamin
                                                                        </option>
                                                                        <option value="laki-laki"
                                                                            {{ $item->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>
                                                                            Laki-laki</option>
                                                                        <option value="perempuan"
                                                                            {{ $item->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>
                                                                            Perempuan</option>
                                                                    </select>
                                                                @elseif($type == 'email')
                                                                    <input type="email"
                                                                        id="{{ $type }}_{{ $item->id }}"
                                                                        name="{{ $type }}"
                                                                        placeholder="Masukan email..."
                                                                        value="{{ $item->$type }}"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300" />
                                                                @elseif($type == 'no_telp')
                                                                    <input type="tel"
                                                                        id="{{ $type }}_{{ $item->id }}"
                                                                        name="{{ $type }}"
                                                                        placeholder="Masukan nomor telepon..."
                                                                        value="{{ $item->$type }}"
                                                                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300" />
                                                                @else
                                                                    <input type="text"
                                                                        id="{{ $type }}_{{ $item->id }}"
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
                                                onclick="initDelete('customer', {{ $item }})" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">
                            {{ $customer->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>

<dialog id="tambah_customer_modal" class="modal modal-bottom sm:modal-middle">
    <form method="POST" class="modal-box rounded-xl shadow-lg" action="{{ route('store.customer') }}"
        enctype="multipart/form-data">
        @csrf
        <h3 class="text-xl font-bold text-gray-800">Tambah Customer</h3>
        <div class="modal-body mt-5 space-y-4">
            @csrf
            @foreach (['nama', 'jenis_kelamin', 'alamat', 'email', 'no_telp'] as $type)
                <div class="flex flex-col gap-2">
                    <label for="{{ $type }}" class="text-sm font-medium text-gray-600 capitalize">
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </label>
                    @if ($type == 'jenis_kelamin')
                        <select name="jenis_kelamin" id="jenis_kelamin"
                            class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                    @elseif($type == 'email')
                        <input type="email" id="{{ $type }}" name="{{ $type }}"
                            placeholder="Masukan email..." value="{{ old($type) }}"
                            class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300" />
                    @elseif($type == 'no_telp')
                        <input type="tel" id="{{ $type }}" name="{{ $type }}"
                            placeholder="Masukan nomor telepon..." value="{{ old($type) }}"
                            class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300" />
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
            <button type="button" onclick="document.getElementById('tambah_customer_modal').close()"
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
