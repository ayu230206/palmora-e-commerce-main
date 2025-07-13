<x-dashboard.main title="Produsen">
    <!-- Kartu Ringkasan -->
    <div class="grid sm:grid-cols-2 gap-5 md:gap-6">
        @foreach (['total_produsen_terdaftar', 'produsen_terbaru'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="{{ $type == 'total_produsen_terdaftar' ? 'bg-blue-300' : 'bg-amber-300' }} p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600 line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 line-clamp-1">
                        {{ $type == 'total_produsen_terdaftar' ? $produsen->count() ?? '-' : '' }}
                        {{ $type == 'produsen_terbaru' ? $produsen_terbaru->nama ?? 'Belum ada produsen terbaru' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tombol Tambah -->
    <div class="flex flex-col lg:flex-row gap-5 mt-6">
        <div onclick="tambah_produsen_modal.showModal()"
            class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
            <div>
                <h1 class="font-semibold sm:text-lg capitalize">Tambah Produsen</h1>
                <p class="text-sm opacity-60">Tambahkan data produsen baru</p>
            </div>
            <x-lucide-plus class="size-6 sm:size-7 opacity-60" />
        </div>
    </div>

    <!-- Tabel -->
    <div class="flex flex-col border-back rounded-xl w-full mt-6">
        <div class="p-5 sm:p-7 bg-white rounded-t-xl">
            <h1 class="font-semibold text-lg capitalize">Manajemen Produsen</h1>
            <p class="text-sm opacity-60">Kelola data produsen produk olahan sawit.</p>
        </div>
        <div class="px-5 sm:px-7 bg-zinc-50">
            <input type="text" placeholder="Cari produsen..." class="input input-sm shadow-md w-full bg-zinc-100">
        </div>
        <div class="overflow-x-auto bg-zinc-50 p-5 sm:p-7 rounded-b-xl">
            <table class="table table-zebra">
                <thead>
                    <tr class="text-center">
                        @foreach (['No', 'Nama', 'Alamat', 'Email', 'No Telp', 'Terdaftar', 'Update Terakhir', ''] as $item)
                            <th class="uppercase font-bold">{{ $item }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produsen as $i => $item)
                        <tr>
                            <th class="text-center">{{ $i + 1 }}</th>
                            <td class="text-center">{{ $item->nama }}</td>
                            <td class="text-center">{{ $item->alamat }}</td>
                            <td class="text-center">{{ $item->email }}</td>
                            <td class="text-center">{{ $item->telp }}</td>
                            <td class="text-center">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="text-center">{{ $item->updated_at->format('d M Y') }}</td>
                            <td class="flex items-center justify-center gap-3">
                                <!-- Edit -->
                                <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                    onclick="document.getElementById('update_modal_{{ $item->id }}').showModal();" />

                                <!-- Delete -->
                                <form action="{{ route('destroy.produsen', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus produsen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer" />
                                    </button>
                                </form>

                                <!-- Modal Update -->
                                <dialog id="update_modal_{{ $item->id }}"
                                    class="modal modal-bottom sm:modal-middle">
                                    <form method="POST" class="modal-box rounded-xl shadow-lg"
                                        action="{{ route('update.produsen', $item->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <h3 class="text-xl font-bold text-gray-800 mb-4">Update Produsen</h3>
                                        @foreach (['nama', 'alamat', 'email', 'telp'] as $field)
                                            <div class="mb-3">
                                                <label for="{{ $field }}_{{ $item->id }}"
                                                    class="text-sm font-medium text-gray-600 capitalize block mb-1">
                                                    {{ ucfirst($field) }}
                                                </label>
                                                <input type="text" name="{{ $field }}"
                                                    id="{{ $field }}_{{ $item->id }}"
                                                    value="{{ $item->$field }}"
                                                    class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300">
                                            </div>
                                        @endforeach
                                        <div class="modal-action mt-4">
                                            <button type="button"
                                                onclick="document.getElementById('update_modal_{{ $item->id }}').close()"
                                                class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 border-0">
                                                Batal
                                            </button>
                                            <button type="submit" onclick="closeAllModals(event)"
                                                class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </dialog>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard.main>

<!-- Modal Tambah -->
<dialog id="tambah_produsen_modal" class="modal modal-bottom sm:modal-middle">
    <form method="POST" class="modal-box rounded-xl shadow-lg" action="{{ route('store.produsen') }}">
        @csrf
        <h3 class="text-xl font-bold text-gray-800">Tambah Produsen</h3>
        <div class="modal-body mt-5 space-y-4">
            @foreach (['nama', 'alamat', 'email', 'telp'] as $field)
                <div class="flex flex-col gap-2">
                    <label for="{{ $field }}" class="text-sm font-medium text-gray-600 capitalize">
                        {{ ucfirst($field) }}
                    </label>
                    <input type="text" name="{{ $field }}" id="{{ $field }}"
                        class="input input-bordered w-full bg-gray-50 border-gray-300 focus:ring focus:ring-blue-300"
                        value="{{ old($field) }}" placeholder="Masukkan {{ $field }}...">
                    @error($field)
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach
        </div>
        <div class="modal-action mt-6">
            <button type="button" onclick="document.getElementById('tambah_produsen_modal').close()"
                class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 border-0">
                Batal
            </button>
            <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0" onclick="closeAllModals(event)">
                Simpan
            </button>
        </div>
    </form>
</dialog>
