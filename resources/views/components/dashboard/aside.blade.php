<div class="drawer-side border-r z-20">
    <label for="aside-dashboard" aria-label="close sidebar" class="drawer-overlay"></label>
    <ul
        class="menu p-4 w-64 lg:w-72 min-h-full bg-white [&>li>a]:gap-4 [&>li]:my-1.5 [&>li]:text-[14.3px] [&>li]:font-medium [&>li]:text-opacity-80 [&>li]:text-base [&>_*_svg]:stroke-[1.5] [&>_*_svg]:size-[23px] [&>.label]:mt-6">

        <!-- Brand Section -->
        <div class="pb-4 border-b border-gray-300">
            @include('components.brands', ['class' => '!text-xl'])
        </div>

        @if(Auth::user()->role === 'admin')
        <!-- General Section -->
        <span class="label text-xs font-extrabold opacity-50">GENERAL</span>
        <li>
            <a href="{{ route('dashboard') }}" class="{!! Request::path() == 'dashboard' ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-bar-chart-2 />
                Dashboard
            </a>
        </li>

        <span class="label text-xs font-extrabold opacity-50 mt-4">DATA</span>
        <li>
            <a href="{{ route('produk') }}" class="{!! Request::is('dashboard/produk*') ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-shopping-basket />
                Data Produk
            </a>
        </li>
        <li>
            <a href="{{ route('customer') }}" class="{!! Request::is('dashboard/customer*') ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-users />
                Data Customer
            </a>
        </li>
        <li>
            <a href="{{ route('kategori') }}" class="{!! Request::is('dashboard/kategori*') ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-list />
                Data Kategori
            </a>
        </li>
        <li>
            <a href="{{ route('produsen') }}" class="{!! Request::is('dashboard/produsen*') ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-pencil />
                Data Produsen
            </a>
        </li>
        <li>
            <a href="{{ route('transaksi') }}" class="{!! Request::is('dashboard/transaksi*') ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-shopping-cart />
                Data Transaksi
            </a>
        </li>

        @else (Auth::user()->role === 'produsen')
            <span class="label text-xs font-extrabold opacity-50">GENERAL</span>
            <li>
                <a href="{{ route('dashboard') }}" class="{!! Request::path() == 'dashboard' ? 'active' : '' !!} flex items-center px-2.5">
                    <x-lucide-bar-chart-2 />
                    Dashboard
                </a>
            </li>
            <span class="label text-xs font-extrabold opacity-50 mt-4">DATA</span>
            <li>
                <a href="{{ route('produk') }}" class="{!! Request::is('dashboard/produk*') ? 'active' : '' !!} flex items-center px-2.5">
                    <x-lucide-shopping-basket />
                    Data Produk
                </a>
            </li>
            <li>
                <a href="{{ route('kategori') }}" class="{!! Request::is('dashboard/kategori*') ? 'active' : '' !!} flex items-center px-2.5">
                    <x-lucide-list />
                    Data Kategori
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi') }}" class="{!! Request::is('dashboard/transaksi*') ? 'active' : '' !!} flex items-center px-2.5">
                    <x-lucide-shopping-cart />
                    Data Transaksi
                </a>
            </li>
        @endif

        <!-- Logout Section -->
        <span class="label text-xs font-extrabold opacity-50 mt-4">ADVANCE</span>
        <li>
            <a class="flex items-center px-2.5 gap-4 cursor-pointer"
                onclick="document.getElementById('logout_modal').showModal();">
                <x-lucide-log-out />
                Logout
            </a>
        </li>
    </ul>
</div>

<dialog id="logout_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box rounded-xl shadow-lg">
        <h3 class="text-xl font-bold text-gray-800">Konfirmasi Logout</h3>
        <p class="mt-2 text-gray-600">Apakah Anda yakin ingin keluar?</p>
        <div class="modal-action mt-4">
            <button type="button" onclick="document.getElementById('logout_modal').close()"
                class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 border-0">
                Batal
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary bg-red-600 hover:bg-red-700 text-white border-0"
                    onclick="closeAllModals(event)">
                    Logout
                </button>
            </form>
        </div>
    </div>
</dialog>
