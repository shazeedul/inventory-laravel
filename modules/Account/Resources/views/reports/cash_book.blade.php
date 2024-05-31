<x-app-layout>
    @include('account::reports.header')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <x-data-table :dataTable="$dataTable" />
                </div>
            </div>
        </div>
    </div>
    <div id="page-axios-data" data-table-id="#cashbook-table"></div>
</x-app-layout>
