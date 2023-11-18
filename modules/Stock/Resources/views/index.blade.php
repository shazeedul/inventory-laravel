<x-app-layout>
    <x-card>
        <x-slot name="actions">
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#stock-table"></div>
</x-app-layout>
