<!--Global script(used by all pages)-->
<!-- core:js -->
<script src="{{ admin_asset('vendors/core/core.min.js') }}"></script>
<script src="{{ admin_asset('vendors/feather-icons/feather.min.js') }}"></script>
<!-- endinject -->
@stack('lib-scripts')
<script src="{{ nanopkg_asset('vendor/jquery-ui-1.13.2/jquery-ui.min.js') }}"></script>
<script src="{{ nanopkg_asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ nanopkg_asset('vendor/fontawesome-free-6.3.0-web/js/all.min.js') }}"></script>
<script src="{{ nanopkg_asset('vendor/toastr/build/toastr.min.js') }}"></script>
<script src="{{ nanopkg_asset('vendor/axios/dist/axios.min.js') }}"></script>
<script src="{{ nanopkg_asset('vendor/nprogress/nprogress.min.js') }}"></script>
<script src="{{ nanopkg_asset('js/nprogress-init.min.js') }}"></script>
<script src="{{ nanopkg_asset('vendor/typed.js/lib/typed.min.js') }}"></script>
<script src="{{ nanopkg_asset('vendor/jquery-validation-1.19.5/jquery.validate.min.js') }}"></script>
<script src="{{ nanopkg_asset('js/axios.init.min.js') }}"></script>
<script src="{{ nanopkg_asset('js/arrow-hidden.min.js') }}"></script>
<script src="{{ nanopkg_asset('js/img-src.min.js') }}"></script>
<script src="{{ nanopkg_asset('js/delete.min.js') }}"></script>
<script src="{{ nanopkg_asset('js/user-status-update.min.js') }}"></script>
<script src="{{ nanopkg_asset('js/main.min.js') }}"></script>
<script src="{{ nanopkg_asset('js/menu-search.min.js') }}"></script>

<!-- inject:js -->
<script src="{{ admin_asset('js/template.min.js') }}"></script>
<!-- endinject -->
@stack('js')
