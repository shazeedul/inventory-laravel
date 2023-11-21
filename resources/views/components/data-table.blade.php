{{-- <div class="table-responsive"> --}}
{!! $dataTable->table(['class' => 'table table-bordered'], true) !!}
{{-- </div> --}}

@push('lib-styles')
    <link rel="stylesheet" href="{{ nanopkg_asset('vendor/yajra-laravel-datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ nanopkg_asset('vendor/yajra-laravel-datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ nanopkg_asset('vendor/yajra-laravel-datatables/datatables.custom.min.css') }}">
@endpush
@push('lib-scripts')
    <script src="{{ nanopkg_asset('vendor/yajra-laravel-datatables/datatables.min.js') }}"></script>
    <script src="{{ nanopkg_asset('vendor/yajra-laravel-datatables/dataTables.responsive.min.js') }}"></script>
@endpush

@push('js')
    {{ $dataTable->scripts() }}
@endpush
