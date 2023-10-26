<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<!-- End fonts -->
@vite(['resources/sass/app.scss', 'resources/js/app.js'])

<!-- core:css -->
<link rel="stylesheet" href="{{ admin_asset('vendors/core/core.min.css') }}">
<!-- endinject -->
<!-- Plugin css for this page -->
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="{{ admin_asset('fonts/feather-font/css/iconfont.min.css') }}">
<link rel="stylesheet" href="{{ admin_asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
<link rel="stylesheet" href="{{ nanopkg_asset('vendor/fontawesome-free-6.3.0-web/css/all.min.css') }}">
<link rel="stylesheet" href="{{ nanopkg_asset('vendor/nprogress/nprogress.min.css') }}">
<!-- endinject -->
@stack('lib-styles')

<link rel="stylesheet" href="{{ nanopkg_asset('vendor/jquery-ui-1.13.2/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ nanopkg_asset('vendor/highlight/highlight.min.css') }}">
<link href="{{ nanopkg_asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('vendor/bootstrap-icons/css/bootstrap-icons.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('css/arrow-hidden.min.css') }}" rel="stylesheet">

<!-- Layout styles -->
<link rel="stylesheet" href="{{ admin_asset('css/demo1/style.min.css') }}">
<link href="{{ nanopkg_asset('vendor/toastr/build/toastr.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('css/custom.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('css/theme.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ nanopkg_asset('css/preloader.min.css') }}">
<link rel="stylesheet" href="{{ nanopkg_asset('css/online-status.min.css') }}">

@stack('css')
<style>
    /* Override NProgress z-index */
    .nprogress {
        z-index: 999;
        /* Adjust the value to be lower than your modal's z-index */
    }
</style>
