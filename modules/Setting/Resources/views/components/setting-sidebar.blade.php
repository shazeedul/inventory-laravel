<nav class="setting-nav card py-2 sub-side-bar p-2 py-3">
    <ul class=" nav">
        <li class="nav-item dropdown {{ request()->has('g') ? 'mm-active' : '' }}">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                aria-expanded="false">
                <i class="link-icon" data-feather="layers"></i>
                @localize('General Settings')
            </a>
            <ul class="dropdown-menu">
                @foreach (Modules\Setting\Facades\Setting::onlyGroup() as $group)
                    <li class="{{ request()?->g == $group ? 'mm-active' : null }}">
                        <a href="{{ route('admin.setting.index', ['g' => $group]) }}"
                            class="dropdown-item settings-goroup">{{ $group }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="nav-item {{ active_menu(route('admin.setting.create'), 'mm-active') }} ">
            <a href="{{ route('admin.setting.create') }}">
                <i class="link-icon" data-feather="plus-square"></i>
                @localize('Create New Setting')
            </a>
        </li>
        @if (can('mail_setting_management') && Route::has('admin.setting.mail.index'))
            <li class="nav-item {{ active_menu(route('admin.setting.mail.index'), 'mm-active') }} ">
                <a href="{{ route('admin.setting.mail.index') }}">
                    <i class="link-icon" data-feather="mail"></i>
                    @localize('Mail Setting')
                </a>
            </li>
        @endif
        @if (can('recaptcha_setting_management') && Route::has('admin.setting.recaptcha.index'))
            <li class="nav-item {{ active_menu(route('admin.setting.recaptcha.index'), 'mm-active') }} ">
                <a href="{{ route('admin.setting.recaptcha.index') }}">
                    <i class="link-icon" data-feather="lock"></i>
                    @localize('Recaptcha Setting')
                </a>
            </li>
        @endif
        @if (can('env_setting_management') && Route::has('admin.setting.env.index'))
            <li class="nav-item {{ active_menu(route('admin.setting.env.index'), 'mm-active') }} ">
                <a href="{{ route('admin.setting.env.index') }}">
                    <i class="link-icon" data-feather="file-text"></i>
                    @localize('.ENV Setting')
                </a>
            </li>
        @endif
        @if (can('language_setting_management') && Route::has('admin.language.index'))
            <li class="nav-item {{ active_menu(route('admin.language.index'), 'mm-active') }} ">
                <a href="{{ route('admin.language.index') }}">
                    <i class="link-icon" data-feather="flag"></i>
                    @localize('Language')
                </a>
            </li>
        @endif
        @if (Route::has('artisan-http.storage-link'))
            <li class="nav-item {{ active_menu(route('artisan-http.storage-link'), 'mm-active') }} ">
                <a href="javascript:void(0);" onclick="storageLink('{{ route('artisan-http.storage-link') }}')">
                    <i class="link-icon" data-feather="link"></i>
                    @localize('Fix Storage Link')
                </a>
            </li>
        @endif
    </ul>
</nav>

@push('css')
    <link rel="stylesheet" href="{{ module_asset('Setting/css/main.min.css') }}">
@endpush
