<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="javascript:void(0);" class="btn btn-primary btn-sm me-2"
                onclick="axiosModal('{{ route(config('theme.rprefix') . '.close') }}')"><i
                    class="fa fa-close"></i>&nbsp;@localize('Close Financial Year')</a>
            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                onclick="axiosModal('{{ route(config('theme.rprefix') . '.create') }}')"><i
                    class="fa fa-plus-circle"></i>&nbsp;@localize('Add Financial Year')</a>
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#financial_year-table"></div>
</x-app-layout>
