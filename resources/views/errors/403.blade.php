<x-guest-layout class="bg-white">
    <div class="page-content d-flex align-items-center justify-content-center">

        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
                <img src="{{ admin_asset('images/error/403.svg') }}" class="img-fluid mb-2" alt="404"
                    style="max-width: 450px;">
                <h1 class="fw-bolder mb-22 mt-2 tx-80 text-muted">403</h1>
                <h4 class="mb-2">Forbidden!</h4>
                <h6 class="text-muted mb-3 text-center">
                    <b>403 - Forbidden:</b>
                    {{ $exception->getMessage() ?: __("You don't have permission to access on the server.") }}
                </h6>
                <a href="{{ back_url() }}">Back</a>
            </div>
            <div class="col-md-12 mt-5">
                <x-admin.guest-footer class="text-center text-black" />

            </div>
        </div>
    </div>

</x-guest-layout>
