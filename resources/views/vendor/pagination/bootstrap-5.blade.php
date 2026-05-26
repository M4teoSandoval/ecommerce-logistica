@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center" style="gap:4px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" style="border:none;border-radius:8px;padding:8px 14px;font-size:0.82rem;color:#94a3b8;background:#f1f5f9;">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="border:none;border-radius:8px;padding:8px 14px;font-size:0.82rem;color:#475569;background:#f8fafc;transition:all 0.2s;">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link" style="border:none;border-radius:8px;padding:8px 14px;font-size:0.82rem;color:#94a3b8;background:transparent;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link" style="border:none;border-radius:8px;padding:8px 14px;font-size:0.82rem;font-weight:600;color:#fff;background:#6366f1;box-shadow:0 2px 8px rgba(99,102,241,0.3);">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="border:none;border-radius:8px;padding:8px 14px;font-size:0.82rem;color:#475569;background:#f8fafc;transition:all 0.2s;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="border:none;border-radius:8px;padding:8px 14px;font-size:0.82rem;color:#475569;background:#f8fafc;transition:all 0.2s;">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" style="border:none;border-radius:8px;padding:8px 14px;font-size:0.82rem;color:#94a3b8;background:#f1f5f9;">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
