<x-app-layout>
    @include('account::vouchers.header')
    <x-card>
        <x-slot name="actions">
            <a href="{{ route(config('theme.rprefix') . '.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i>&nbsp;
                @localize('Add Journal Voucher')
            </a>
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#journal-voucher-table"></div>
</x-app-layout>
