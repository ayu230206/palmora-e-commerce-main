<!DOCTYPE html>
<html lang="en" data-theme="emerald">

<head>
    @include('components.head')
    <style>
        .toast {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .toast-show {
            opacity: 1;
        }

        @keyframes spin-reverse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-360deg);
            }
        }

        .reverse-spin {
            animation: spin-reverse 1s linear infinite;
        }
    </style>

</head>

<body class="flex flex-col mx-auto min-h-screen">
    <div id="splash-screen"
        class="fixed inset-0 z-[9999] flex items-center justify-center min-h-screen bg-gradient-to-br from-yellow-100 via-[#d4e4b4] to-green-100 transition-opacity duration-500 opacity-100 min-h-screen">

        <div
            class="relative flex flex-col items-center justify-center p-10 bg-white/80 rounded-[2rem] shadow-2xl border border-green-200 backdrop-blur-lg transition-transform duration-700 scale-100">

            <!-- Loading Animation -->
            <div class="relative w-24 h-24 mb-6">
                <div
                    class="absolute inset-0 rounded-full border-[10px] border-t-green-600 border-r-transparent border-b-green-600 border-l-transparent animate-spin">
                </div>
                <div
                    class="absolute inset-2 rounded-full border-[10px] border-t-yellow-400 border-r-transparent border-b-yellow-400 border-l-transparent animate-spin reverse-spin">
                </div>
            </div>

            <!-- Branding -->
            <h1 class="text-4xl font-extrabold text-green-700 drop-shadow-sm tracking-widest">PALMORA</h1>
            <p class="text-sm mt-2 italic text-gray-600">Memuat data produk dan sistem...</p>
        </div>
    </div>

    @if (!str_contains(request()->path(), 'dashboard') && !request()->is('login') && !request()->is('register'))
        @include('components.navbar')
    @endif

    <main class="{{ $class ?? 'p-4' }}" role="main">

        {{ $slot }}
        <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-4"></div>

        <script>
            function showToast(message, type) {
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');

                toast.classList.add(
                    'relative', 'shadow-lg', 'bg-white', 'p-4', 'rounded-lg', 'flex',
                    'items-center', 'justify-between', 'border-l-4', `border-${type}`,
                    'transition-transform', 'transition-opacity', 'transform', 'duration-300', 'ease-in-out',
                    'opacity-0', 'translate-x-full'
                );

                toast.innerHTML = `
            <div class="flex-grow flex items-center space-x-2">
                <span class="font-semibold">${message}</span>
            </div>
            <button class="ml-4 btn btn-sm btn-circle btn-ghost" onclick="this.parentElement.remove()">✕</button>
        `;

                toastContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 15000);
            }

            @if (session('toast'))
                showToast('{{ session('toast.message') }}', '{{ session('toast.type') }}');
            @endif
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var splashScreen = document.getElementById('splash-screen');
                document.body.classList.add('overflow-hidden');

                splashScreen.classList.add('show');

                window.addEventListener('load', function() {
                    splashScreen.classList.remove('show');
                    splashScreen.classList.add('opacity-0', 'pointer-events-none');
                    document.body.classList.remove('overflow-hidden');
                });
            });

            window.addEventListener('beforeunload', function() {
                var splashScreen = document.getElementById('splash-screen');
                splashScreen.classList.add('show');
                document.body.classList.add('overflow-hidden');
            });
        </script>

        @if (!str_contains(request()->path(), 'dashboard') && !request()->is('login') && !request()->is('register'))
            @include('components.footer')
        @endif
    </main>
</body>

</html>
