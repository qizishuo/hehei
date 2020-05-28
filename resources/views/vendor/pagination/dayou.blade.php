@if ($paginator->hasPages())
    <div class="row">
        <div class="page_bot_left" style="margin-left:15px;">
            <div class="dataTables_paginate">
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="disabled">
                            <span aria-hidden="true"> < </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}"> < </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="disabled"><span>{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}"> > </a>
                        </li>
                    @else
                        <li class="disabled">
                            <span aria-hidden="true"> > </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <!--总条数-->
        <div class="page_bot_left">共 {{ $paginator->total() }} 条</div>
        <!--几条/页 开始-->
        <div class="page_bot_left">
            <div class="dataTables_length">
                <label>
                    <select id="page-size" class="form-control">
                        @foreach([5, 10, 15, 20] as $page_size)
                            @if($page_size == $paginator->perPage())
                                <option selected value="{{ $page_size }}">{{ $page_size }}</option>
                            @else
                                <option value="{{ $page_size }}">{{ $page_size }}</option>
                            @endif
                        @endforeach
                    </select> 条/页
                </label>
            </div>
        </div>
    </div>
    <script>
        let pageSizeElement = document.getElementById("page-size");
        pageSizeElement.onchange = function (event) {
            let pageSize = event.target.value;
            let search = location.search;
            let params = search.split("?")[1];
            if (!params) {
                location.search = "?page_size=" + pageSize;
            } else {
                params = params.split("&");
                for (let i = 0; i < params.length; ++i) {
                    let param = params[i].split("=");
                    if (params[i][0] === "page_size") {
                        params[i] = "page_size=" + pageSize;
                        location.search = "?" + params.join("&");
                        return
                    }
                }
                params.push("page_size=" + pageSize);
                location.search = "?" + params.join("&");
            }
        };
    </script>
@endif
