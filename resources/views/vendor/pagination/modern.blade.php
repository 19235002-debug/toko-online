@if ($paginator->hasPages())
<style>
    .modern-pagination-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .modern-pagination {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        list-style: none;
        margin: 0;
    }
    .modern-pagination .mp-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border-radius: 11px;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        background: transparent;
        text-decoration: none !important;
        transition: all 0.18s ease;
        user-select: none;
    }
    .modern-pagination a.mp-link:hover {
        background: #eef2ff;
        color: #4f46e5;
        transform: translateY(-1px);
    }
    .modern-pagination .mp-item.active .mp-link {
        background: linear-gradient(135deg, #717fe0 0%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.28);
    }
    .modern-pagination .mp-item.disabled .mp-link {
        color: #cbd5e1;
        cursor: not-allowed;
    }
    .modern-pagination .mp-dots {
        padding: 0 6px;
        color: #94a3b8;
        font-weight: 600;
        font-size: 13px;
    }
    .modern-pagination-info {
        font-size: 12px;
        color: #64748b;
    }
</style>

<nav aria-label="Navigasi Halaman" class="modern-pagination-wrap">
    <ul class="modern-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="mp-item disabled" aria-disabled="true" aria-label="Sebelumnya">
                <span class="mp-link" aria-hidden="true"><i class="fa fa-angle-left"></i></span>
            </li>
        @else
            <li class="mp-item">
                <a class="mp-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"><i class="fa fa-angle-left"></i></a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="mp-item disabled" aria-disabled="true"><span class="mp-dots">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="mp-item active" aria-current="page"><span class="mp-link">{{ $page }}</span></li>
                    @else
                        <li class="mp-item"><a class="mp-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="mp-item">
                <a class="mp-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya"><i class="fa fa-angle-right"></i></a>
            </li>
        @else
            <li class="mp-item disabled" aria-disabled="true" aria-label="Berikutnya">
                <span class="mp-link" aria-hidden="true"><i class="fa fa-angle-right"></i></span>
            </li>
        @endif
    </ul>

    <div class="modern-pagination-info">
        Menampilkan <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong> dari <strong>{{ $paginator->total() }}</strong> data
    </div>
</nav>
@endif

