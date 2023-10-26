<footer
    {{ $attributes->merge([
        'class' => 'footer d-flex flex-column flex-md-row align-items-center
                                    justify-content-between px-4 py-3 border-top small',
    ]) }}>
    <p class="text-muted mb-1 mb-md-0">
        @localize('Copyright') @localize('©') {{ date('Y') }}
        <a class="text-capitalize text-black" href="{{ config('app.url') }}"
            target="_blank">{{ ___(config('app.name')) }}</a>.
    </p>
    <p class="text-muted">
        @localize('Handcrafted With')
        <i class="mb-1 text-primary ms-1 icon-sm" data-feather="heart"></i>
        <a class="text-capitalize text-black" href="https://iqbalhasan.dev" target="_blank">
            @localize('IQBAL HASAN')
        </a>
    </p>
</footer>
