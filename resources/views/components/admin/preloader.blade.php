<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="spinner-border" role="status"></div>
        <p>@localize('Please wait')...</p>
    </div>
</div>

@push('js')
    <script src="{{ nanopkg_asset('js/preloader.min.js') }}"></script>
@endpush
