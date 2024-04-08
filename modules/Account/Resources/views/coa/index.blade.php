<x-app-layout>
    @push('lib-styles')
        <link rel="stylesheet" href="{{ module_asset('account/themes/default/style.min.css') }}">
    @endpush
    @push('css')
        <style>
            .ui-dialog .ui-dialog-titlebar-close {
                position: absolute;
                right: 0.3em;
                top: 50%;
                width: 20px;
                margin: -10px 0 0 0;
                padding: 1px;
                height: 26px;
            }

            .dataTable tbody input,
            .dataTable tbody select,
            .dataTable tfoot input,
            .dataTable tfoot select {
                width: 100%;
                box-sizing: border-box;
                border: 1px solid #e4e5e7;
                height: 15px;
                padding: 6px 12px;
                padding: 0.375rem 0.75rem;
                border-radius: 0.25rem;
            }

            .chart-form {
                position: fixed;
                top: 150px;
                background: #fdfbf4;
                padding: 30px;
            }
        </style>
    @endpush
    <x-card>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="search mb-2">
                    <div class="search__inner tree-search">
                        <input id="treesearch" type="search" class="form-control search__text"
                            placeholder="@localize('Tree Search')" autocomplete="off">
                        <i class="typcn typcn-zoom-outline search__helper" data-sa-action="search-close"></i>
                    </div>
                </div>
                {{-- @include('accounts::coa.subblade.coatree') --}}
                <div id="jstree">
                    <ul>
                        <li data-jstree='{ "opened" : true }'>{{ localize('COA') }}
                            <ul>
                                @forelse ($accMainHead as $acHeadKye => $accHeadValue)
                                    <li data-jstree='{ "selected" : false }' data-id="{{ $accHeadValue->id }}">
                                        {{ $accHeadValue->name }} - {{ $accHeadValue->code }}
                                        @foreach ($accSecondLabelHead as $acSecondHeadKye => $accSecondHeadValue)
                                            @if ($accSecondHeadValue->parent_id == $accHeadValue->id)
                                                <ul>
                                                    <li data-jstree='{ "selected" : false }'
                                                        data-id="{{ $accSecondHeadValue->id }}">
                                                        {{ $accSecondHeadValue->name }} -
                                                        {{ $accSecondHeadValue->code }}
                                                        @if ($accHeadWithoutFands->where('parent_id', $accSecondHeadValue->id)->isNotEmpty())
                                                            @foreach ($accHeadWithoutFands->where('parent_id', $accSecondHeadValue->id) as $allOtherKey => $accHeadWithoutFansValue)
                                                                <ul>
                                                                    <li data-jstree='{ "selected" : false }'
                                                                        data-id="{{ $accHeadWithoutFansValue->id }}">
                                                                        {{ $accHeadWithoutFansValue->name }} -
                                                                        {{ $accHeadWithoutFansValue->code }}
                                                                        @if ($accHeadWithoutFands->where('parent_id', $accHeadWithoutFansValue->id)->isNotEmpty())
                                                                            @foreach ($accHeadWithoutFands->where('parent_id', $accHeadWithoutFansValue->id) as $allFourthKey => $fourthLabelValue)
                                                                                <ul>
                                                                                    <li data-jstree='{ "selected" : false }'
                                                                                        data-id="{{ $fourthLabelValue->id }}">
                                                                                        {{ $fourthLabelValue->name }} -
                                                                                        {{ $fourthLabelValue->code }}
                                                                                    </li>
                                                                                </ul>
                                                                            @endforeach
                                                                        @endif
                                                                    </li>
                                                                </ul>
                                                            @endforeach
                                                        @endif
                                                    </li>
                                                </ul>
                                            @endif
                                        @endforeach
                                    </li>
                                @empty
                                    <li>@localize('No Data Found')</li>
                                @endforelse
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                {{-- @include('accounts::coa.subblade.coafrom') --}}
            </div>
        </div>
    </x-card>
    @push('lib-scripts')
        <script src="{{ module_asset('account/js/jstree.min.js') }}"></script>
        <script src="{{ module_asset('account/js/tree-view.active.js') }}"></script>
    @endpush
    @push('js')
        <script>
            $(function() {
                $('#jstree').jstree({
                    'core': {
                        'themes': {
                            'responsive': false,
                            'dots': true,
                            'icons': true,
                        },
                        "check_callback": true
                    },
                    'types': {
                        "default": {
                            'icon': "fas fa-folder text-warning"
                        },
                        'file': {
                            'icon': "fas fa-file text-info"
                        }
                    },
                    'plugins': ["types", "wholerow", "search"]
                });
                var to = false;
                $('#treesearch').keyup(function() {
                    if (to) {
                        clearTimeout(to);
                    }
                    to = setTimeout(function() {
                        var v = $('#treesearch').val();
                        $('#jstree').jstree(true).search(v);
                    }, 250);
                });
            });
        </script>
    @endpush
</x-app-layout>
