@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="mt-12 flex justify-center">
        <ul class="inline-flex space-x-1 rounded-xl bg-white px-2 py-2 shadow-md">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span
                        class="inline-flex items-center px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-l-xl cursor-not-allowed">
                        <x-lucide-chevron-left class="w-4 h-4" />
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                        class="inline-flex items-center px-3 py-2 text-sm text-green-600 bg-yellow-100 hover:bg-yellow-200 rounded-l-xl transition">
                        <x-lucide-chevron-left class="w-4 h-4" />
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="inline-flex items-center px-3 py-2 text-sm text-gray-500">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span
                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold bg-green-500 text-white rounded-md shadow">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-yellow-100 rounded-md transition">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                        class="inline-flex items-center px-3 py-2 text-sm text-green-600 bg-yellow-100 hover:bg-yellow-200 rounded-r-xl transition">
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </a>
                </li>
            @else
                <li>
                    <span
                        class="inline-flex items-center px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-r-xl cursor-not-allowed">
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
