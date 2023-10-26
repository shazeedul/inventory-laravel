<x-guest-layout class="bg-white">
    <div class="page-content d-flex align-items-center justify-content-center">

        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
                <img src="{{ admin_asset('images/error/503.svg') }}" class="img-fluid mb-2" alt="404"
                    style="   style="max-width: 450px;">
                <h1 class="fw-bolder mb-22 mt-2 tx-80 text-muted">503</h1>
                <h4 class="mb-2">Service Unavailable!</h4>
                <h6 class="text-muted mb-3 text-center">
                    <b>503 - Service Unavailable:</b> {{ ___('Sorry, I’m currently unable to handleyour request.') }}
                </h6>
                <a href="{{ back_url() }}">Back</a>
            </div>
            <div class="col-md-12 mt-5">
                <x-admin.guest-footer class="text-center text-black" />

            </div>
        </div>
    </div>

</x-guest-layout>
