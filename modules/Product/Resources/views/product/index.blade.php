<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                onclick="axiosModal('{{ route(config('theme.rprefix') . '.create') }}')"><i
                    class="fa fa-plus-circle"></i>&nbsp;@localize('Add New Product')</a>
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#product-table"></div>
    @push('lib-styles')
        <link href="{{ nanopkg_asset('vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
    @endpush
    @push('js')
        <script src="{{ module_asset('Product/js/app.min.js') }}"></script>
    @endpush
</x-app-layout>
