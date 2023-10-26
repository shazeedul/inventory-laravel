<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- meta manager --}}
    <x-meta-manager />
    {{-- favicon --}}
    <x-favicon />
    {{-- style --}}
    <x-admin.styles />
</head>

<body {{ $attributes->merge(['class' => '']) }}>
    <!-- Preloader -->
    <x-admin.preloader />
    <div class="main-wrapper " id="vue-app">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                {{ $slot }}
            </div>
        </div>
    </div>
    <!-- /.End of form wrapper -->
    @stack('modal')
    <!-- start scripts -->
    <x-admin.scripts />
    <!-- end scripts -->
    <x-toster-session />
</body>

</html>
