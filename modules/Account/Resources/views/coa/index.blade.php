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
                /* position: fixed;
                                                                                                    top: 180px; */
                background: #fdfbf4;
                padding: 20px;
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
                @include('account::coa.tree')
            </div>
            <div class="col-md-6 col-sm-12">
                @include('account::coa.form')
            </div>
        </div>
    </x-card>
    <input type="hidden" id="accountSubType" value="{{ json_encode($subTypes) }}">
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
                    'plugins': ["wholerow", "search"]
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
        <script src="{{ module_asset('account/js/app.js') }}"></script>
        <script src="{{ module_asset('account/js/load.js') }}"></script>
    @endpush
</x-app-layout>
