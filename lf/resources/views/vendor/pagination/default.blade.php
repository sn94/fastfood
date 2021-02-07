<style>
ul.pagination li{
    font-size: 16px;
    padding-left: 20px;
   
}
ul.pagination li a,  ul.pagination li span{
    color: black !important;
}
</style>

@if ($paginator->hasPages())
    
    <nav>
        <ul class="pagination ">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled bg-warning text-dark " aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="bg-warning text-dark ">
                    <a onclick='fill_grill(event)' href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled  bg-warning text-dark " aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active bg-warning text-dark " aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li class="bg-warning text-dark "><a onclick='fill_grill(event)' href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="bg-warning text-dark ">
                    <a onclick='fill_grill(event)' href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="disabled bg-warning text-dark " aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
