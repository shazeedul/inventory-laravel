@php
    $id = $attributes['id'] ?? 'multi-nav-' . rand(1000, 9999);
@endphp

<li class="nav-item" {{ $attributes->except('id') }}>
    <a class="nav-link" data-bs-toggle="collapse" href="#{{ $id }}" role="button" aria-expanded="false"
        aria-controls="emails">
        {!! $title !!}
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>
    <div class="collapse" id="{{ $id }}">
        <ul class="nav sub-menu">
            {{ $slot }}
        </ul>
    </div>
</li>
