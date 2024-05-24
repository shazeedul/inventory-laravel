<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="{{ route(config('theme.rprefix') . '.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i>&nbsp;
                @localize('Add Balance')
            </a>
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#opening_balance-table"></div>
</x-app-layout>
