@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="pagination-container">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <span class="page-link disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
        &lsaquo;
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}{{ request()->has('search') ? '&search='.request('search') : '' }}"
       class="page-link" rel="prev" aria-label="@lang('pagination.previous')">
        &lsaquo;
    </a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
    <span class="page-link disabled" aria-disabled="true">{{ $element }}</span>
    @endif

    {{-- Array Of Links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="page-link active" aria-current="page">{{ $page }}</span>
    @else
    <a href="{{ $url }}{{ request()->has('search') ? '&search='.request('search') : '' }}" class="page-link">
        {{ $page }}
    </a>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}{{ request()->has('search') ? '&search='.request('search') : '' }}"
       class="page-link" rel="next" aria-label="@lang('pagination.next')">
        &rsaquo;
    </a>
    @else
    <span class="page-link disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
        &rsaquo;
    </span>
    @endif
</nav>

<style>
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        padding: 10px 0;
    }

    .page-link {
        display: inline-block;
        padding: 3px 15px;
        border: 1px solid darkorange;
        border-radius: 4px;
        text-decoration: none;
        color: darkorange;
        background-color: #fff;
        transition: background-color 0.3s, color 0.3s;
    }

    .page-link:hover {
        background-color: #0056b3;
        color: #fff;
    }

    .page-link.active {
        background-color: darkorange;
        color: #fff;
        border-color: darkorange;
        pointer-events: none;
    }
</style>
@endif
