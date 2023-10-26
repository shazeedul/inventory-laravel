<li class="nav-item {{ active_menu($attributes['href'],'mm-active')}}">
    <a class="text-capitalize nav-link" href="{{ $attributes['href']??'javascript: void(0);' }}">
        {{ $slot }}
    </a>
</li>