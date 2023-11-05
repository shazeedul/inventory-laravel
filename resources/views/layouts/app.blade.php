<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- meta manager --}}
    <x-meta-manager />
    {{-- favicon --}}
    <x-favicon />
    {{-- style --}}
    <x-admin.styles />
    <x-language::localizer />
</head>

<body {{ $attributes->merge(['class' => '']) }}>
    <!-- Preloader -->
    <x-admin.preloader />
    <!-- vue page -->
    <div id="vue-app">
        <!-- Begin page -->
        <div class="main-wrapper">
            <!-- start sidebar -->
            <x-admin.left-sidebar />
            <!-- end sidebar -->
            <div class="page-wrapper">
                <!-- start header -->
                <x-admin.header />
                <!-- end header -->
                <!-- start page content -->
                <div class="page-content">
                    <div class="content-header row align-items-center g-0">
                        <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last text-sm-end mb-3 mb-sm-0">
                            <ol class="breadcrumb rounded d-inline-flex fw-semi-bold fs-13  my-2">
                                @foreach (config('theme.breadcrumb') ?? [] as $b)
                                    @if ($b['link'])
                                        <li class="breadcrumb-item"><a href="{{ $b['link'] }}">@localize($b['name'])</a>
                                        </li>
                                    @else
                                        <li class="breadcrumb-item active">@localize($b['name'])</li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                        <div class="col-sm-8 header-title">
                            <div class="d-flex align-items-center">
                                @if (config('theme.icon'))
                                    <div
                                        class="header-icon d-flex align-items-center justify-content-center rounded shadow-sm text-success flex-shrink-0">
                                        {{ config('theme.icon') }}
                                    </div>
                                @endif
                                <div class="">
                                    {{ $tile ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-content">
                        {{ $slot }}
                    </div>
                </div>
                <x-online-status />
                <x-admin.footer />
            </div>
        </div>
        <!--end  vue page -->
    </div>
    <!-- END layout-wrapper -->

    @stack('modal')
    <x-modal id="delete-modal" title="{{ @localize('Delete Modal') }}">
        <form action="javascript:void(0);" class="needs-validation modal-content" id="delete-modal-form">
            <div class="modal-body">
                <p>@localize("Are you sure you want to delete this item? You won't be able to revert this item back!")
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">@localize('Close')</button>
                <button class="btn btn-danger" type="submit" id="delete_submit">@localize('Delete')</button>
            </div>
        </form>
    </x-modal>
    <!-- start scripts -->
    <x-admin.scripts />
    <!-- end scripts -->
    <x-toster-session />
</body>

</html>
