<x-main title="Masuk" class="p-0" full>
    <section class="min-h-screen flex items-stretch text-white">
        <!-- Left Side / Hero -->
        <div class="lg:flex w-1/2 hidden bg-no-repeat bg-cover relative items-center"
            style="background-image: url('{{ asset('images/pasarpikul.jpg') }}')">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent z-0"></div>
            <div class="w-full px-24 z-10">
                <h2 class="text-4xl leading-tight tracking-wide font-semibold max-w-lg text-yellow-400">
                    Platform Terbaik untuk Produk Olahan Sawit Berkualitas
                </h2>
                <p class="mt-4 text-lg text-gray-200">PALMORA mendukung keberlanjutan dan produk ramah lingkungan
                    Indonesia.</p>
            </div>
            <div class="bottom-0 absolute p-4 text-center right-0 left-0 flex justify-center space-x-4">
                <span>
                    @include('components.brands')
                </span>
            </div>
        </div>

        <!-- Right Side / Form -->
        <div class="lg:w-1/2 w-full bg-white flex items-center justify-center text-center md:px-16 px-0 z-0 relative">
            <!-- Mobile Background -->
            <div class="absolute lg:hidden z-0 inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/bg.jpg') }}')">
                <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
            </div>

            <!-- Login Box -->
            <div class="w-full py-6 z-20">
                <div class="mb-6">
                    @include('components.brands', ['class' => '!text-3xl text-green-600'])
                </div>

                <form action="{{ route('authenticate') }}" method="POST"
                    class="sm:w-2/3 w-full px-4 lg:px-0 mx-auto text-left">
                    @csrf

                    <div class="pb-2 pt-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" required placeholder="Masukkan username..."
                            class="input block w-full p-3 text-base mt-1 bg-gray-100 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-gray-700">
                        @error('username')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pb-2 pt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required
                            placeholder="Masukkan password..."
                            class="input block w-full p-3 text-base mt-1 bg-gray-100 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-gray-700">
                    </div>

                    <div class="pb-2 pt-6">
                        <button type="submit"
                            class="btn w-full text-white font-semibold bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500 shadow-md py-3 rounded-lg transition duration-300">
                            Masuk
                        </button>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('index') }}"
                            class="block w-full text-center text-sm text-green-700 hover:underline mt-2">
                            &larr; Kembali ke Beranda
                        </a>
                    </div>
                </form>

                <div class="mt-8 text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-green-600 font-semibold hover:underline">Daftar
                        Akun</a>
                </div>
            </div>
        </div>
    </section>
</x-main>
