<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                onclick="axiosModal('{{ route(config('theme.rprefix') . '.create') }}')"><i
                    class="fa fa-plus-circle"></i>&nbsp;@localize('Add New Customer')</a>
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#customer-table"></div>
</x-app-layout>
