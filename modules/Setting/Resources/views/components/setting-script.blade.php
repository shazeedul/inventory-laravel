@push('lib-scripts')
    <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
    <script src="{{ nanopkg_asset('vendor/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ nanopkg_asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/theme-clouds.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/mode-json.min.js"></script>
@endpush
@push('js')
    <script src="{{ module_asset('Setting/js/main.js') }}"></script>
    <script src="{{ module_asset('Setting/js/tinymce-config.min.js') }}"></script>
@endpush
