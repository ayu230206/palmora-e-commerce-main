@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Request;
    use App\Models\Customer;
    use App\Models\Keranjang;

    $jumlah_cart = 0;

    if (Auth::check()) {
        $user = Auth::user();
        $customer = Customer::where('user', $user->id)->first();
        if ($customer) {
            $jumlah_cart = Keranjang::where('customer', $customer->id)->count();
        }
    }
@endphp

<nav class="navbar bg-white shadow-md px-4 py-3">
    <div class="navbar-start">
        <a href="{{ route('index') }}" class="flex items-center gap-2 text-green-700 text-2xl font-extrabold tracking-wide">
            PALMORA
        </a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal gap-2">
            <li>
                <a href="{{ route('index') }}"
                   class="font-medium px-3 py-2 rounded-md transition hover:bg-green-100 hover:text-green-800 {{ Request::routeIs('index') ? 'text-green-700 font-semibold underline underline-offset-4' : '' }}">
                    Home
                </a>
            </li>

            @auth
                <li>
                    <a href="{{ route('keranjang') }}"
                       class="relative font-medium px-3 py-2 rounded-md transition hover:bg-green-100 hover:text-green-800 {{ Request::routeIs('keranjang') ? 'text-green-700 font-semibold underline underline-offset-4' : '' }}">
                        Keranjang
                        @if ($jumlah_cart > 0)
                            <span class="badge badge-sm bg-green-600 text-white absolute -top-2 -right-3">
                                {{ $jumlah_cart }}
                            </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('history') }}"
                       class="font-medium px-3 py-2 rounded-md transition hover:bg-green-100 hover:text-green-800 {{ Request::routeIs('history') ? 'text-green-700 font-semibold underline underline-offset-4' : '' }}">
                        History
                    </a>
                </li>
            @endauth
        </ul>
    </div>

    <div class="navbar-end">
        @auth
            <div class="hidden lg:flex">
                <button onclick="document.getElementById('logout_modal').showModal();"
                    class="btn btn-sm bg-red-600 hover:bg-red-700 text-white border-none">
                    Logout
                </button>
            </div>
        @else
            <div class="hidden lg:flex gap-2">
                <a href="{{ route('login') }}"
                    class="btn btn-sm {{ Request::routeIs('login') ? 'btn-success text-white bg-green-700 border-none' : 'btn-outline' }}">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="btn btn-sm {{ Request::routeIs('register') ? 'btn-success text-white bg-green-700 border-none' : 'btn-outline' }}">
                    Register
                </a>
            </div>
        @endauth

        <!-- Hamburger -->
        <div class="dropdown dropdown-end lg:hidden">
            <label tabindex="0" class="btn btn-ghost btn-circle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-green-700" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1000] p-4 shadow-xl bg-white rounded-box w-52 space-y-2">
                <li><a href="{{ route('index') }}">Home</a></li>
                @auth
                    <li><a href="{{ route('keranjang') }}">Keranjang ({{ $jumlah_cart }})</a></li>
                    <li><a href="{{ route('history') }}">History</a></li>
                    <li><button onclick="document.getElementById('logout_modal').showModal();" class="btn btn-sm btn-error w-full">Logout</button></li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Logout Modal -->
<dialog id="logout_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box rounded-xl shadow-xl border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800">Keluar dari Palmora?</h3>
        <p class="mt-2 text-gray-600">Anda akan keluar dari akun saat ini.</p>
        <div class="modal-action">
            <button onclick="document.getElementById('logout_modal').close()" class="btn btn-sm bg-gray-200 text-gray-700 hover:bg-gray-300">
                Batal
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm bg-red-600 hover:bg-red-700 text-white">
                    Logout
                </button>
            </form>
        </div>
    </div>
</dialog>
