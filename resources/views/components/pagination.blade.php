@props(['paginator', 'count' => null])
@php($lastPage = $count ? (int) ceil($count / 16) : $paginator->lastPage())
@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center w-100 mt-3">
        <ul class="pagination flex-wrap justify-content-center mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <a class="page-link" rel="next" aria-label="@lang('pagination.previous')"><span aria-hidden="true">«</span></a>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev" aria-label="@lang('pagination.previous')"><span aria-hidden="true">«</span></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            
            @for($page = 1; $page <= $lastPage; $page++)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" rel="next" aria-label="@lang('pagination.previous')">{{ $page }}</a>
                    </li>
                @else
                    <li class="page-item"><a href="{{ $paginator->url($page) }}" class="page-link">{{ $page }}</a></li>
                @endif
            @endfor

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next" aria-label="@lang('pagination.next')"><span aria-hidden="true">»</span></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <a class="page-link" rel="next" aria-label="@lang('pagination.previous')"><span aria-hidden="true">»</span></a>
                </li>
            @endif
        </ul>
    </nav>
@endif