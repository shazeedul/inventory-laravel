<footer {{ $attributes->merge(['class' => '']) }}>
    <div class="">
        <div class="copy">
            @localize('©') {{ date('Y') }}
            <a class="text-capitalize" href="{{ config('app.url') }}" target="_blank">
                {{ config('app.name') }}
            </a>.
        </div>
        <div class="credit">
            @localize('Handcrafted With')
            <i class="mb-1 text-danger ms-1 icon-sm" data-feather="heart"></i>
            <a class="text-capitalize text-black" href="https://shazeedul.dev" target="_blank">
                @localize('SYED SHAZEEDUL ISLAM')
            </a>

        </div>
    </div>
</footer>
