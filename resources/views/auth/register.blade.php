<x-main title="Daftar" class="p-0" full>
    <section class="min-h-screen flex items-stretch text-white">
        <!-- Gambar Hero -->
        <div class="lg:flex w-1/2 hidden bg-no-repeat bg-cover relative items-center"
            style="background-image: url('{{ asset('images/pasarpikul.jpg') }}')">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent z-0"></div>
            <div class="w-full px-24 z-10">
                <h2 class="text-4xl font-semibold leading-tight tracking-wide max-w-lg text-yellow-400">
                    Bergabung bersama PALMORA dan dukung produk lokal berkualitas.
                </h2>
                <p class="mt-4 text-lg text-gray-200">Ramah lingkungan dan mendukung industri sawit Indonesia.</p>
            </div>
        </div>

        <!-- Form Pendaftaran -->
        <div class="lg:w-1/2 bg-white w-full flex items-center justify-center px-4 py-12 relative text-black">
            <div class="w-full max-w-md space-y-6">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-green-600">Daftar Akun PALMORA</h1>
                </div>

                <!-- Tombol Switch -->
                <div class="flex justify-center gap-4 mb-4">
                    <button type="button" id="btnCustomer"
                        class="px-4 py-2 bg-green-600 text-white rounded font-semibold">Customer</button>
                    <button type="button" id="btnProdusen"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-semibold">Seller</button>
                </div>

                <!-- Form Customer -->
                <form id="formCustomer" method="POST" action="{{ route('auth.register') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" value="customer">

                    @foreach ($customerFields as $field)
                        <div>
                            <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700">
                                {{ $field['label'] }}
                            </label>

                            @if ($field['type'] === 'select')
                                <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" required
                                    class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg">
                                    <option value="" disabled selected>Pilih {{ $field['label'] }}</option>
                                    @foreach ($field['options'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                    id="{{ $field['name'] }}" placeholder="{{ $field['placeholder'] }}" required
                                    class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg">
                            @endif

                            @error($field['name'])
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    <button type="submit" class="w-full py-3 bg-green-600 text-white rounded font-semibold">
                        Daftar sebagai Customer
                    </button>
                </form>

                <!-- Form Produsen -->
                <form id="formProdusen" method="POST" action="{{ route('auth.register.produsen') }}"
                    class="space-y-4 hidden">
                    @csrf
                    <input type="hidden" name="role" value="produsen">

                    @foreach ($produsenFields as $field)
                        <div>
                            <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700">
                                {{ $field['label'] }}
                            </label>

                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                placeholder="{{ $field['placeholder'] }}" required
                                class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg">

                            @error($field['name'])
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    <button type="submit" class="w-full py-3 bg-yellow-500 text-white rounded font-semibold">
                        Daftar sebagai Seller
                    </button>
                </form>


                <div class="text-sm text-center text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">Masuk Akun</a>
                </div>
            </div>
        </div>
    </section>

    <script>
        const btnCustomer = document.getElementById('btnCustomer');
        const btnProdusen = document.getElementById('btnProdusen');
        const formCustomer = document.getElementById('formCustomer');
        const formProdusen = document.getElementById('formProdusen');

        btnCustomer.addEventListener('click', () => {
            formCustomer.classList.remove('hidden');
            formProdusen.classList.add('hidden');
            btnCustomer.classList.replace('bg-gray-200', 'bg-green-600');
            btnCustomer.classList.replace('text-gray-800', 'text-white');
            btnProdusen.classList.replace('bg-yellow-500', 'bg-gray-200');
            btnProdusen.classList.replace('text-white', 'text-gray-800');
        });

        btnProdusen.addEventListener('click', () => {
            formProdusen.classList.remove('hidden');
            formCustomer.classList.add('hidden');
            btnProdusen.classList.replace('bg-gray-200', 'bg-yellow-500');
            btnProdusen.classList.replace('text-gray-800', 'text-white');
            btnCustomer.classList.replace('bg-green-600', 'bg-gray-200');
            btnCustomer.classList.replace('text-white', 'text-gray-800');
        });
    </script>
</x-main>
