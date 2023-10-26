<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <form class="search-form" action="#">
            <div class="input-group">
                <div class="input-group-text">
                    <i data-feather="search"></i>
                </div>
                <input type="search" class="form-control" id="menu-search" oninput="menuSearch(this)"
                    onpaste="menuSearch(this)" placeholder="Search here...">
            </div>
        </form>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="flag"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="languageDropdown">
                    @foreach (getLocalizeLang() as $language)
                        <a class="dropdown-item py-2 {{ $language->code == App::getLocale() ? 'active' : '' }}"
                            href="{{ route('lang.switch', $language->code) }}">
                            <span class="ms-1"> {{ $language->title }}</span>
                        </a>
                    @endforeach
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="mail"></i>
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="messageDropdown">
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                        <p>Messages</p>
                        <a href="javascript:;" class="text-muted">Clear all</a>
                    </div>
                    <div class="p-1">
                        <p class="text-muted d-flex align-items-center py-5 px-5">
                            No new message found.
                        </p>

                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell"></i>
                    {{-- <div class="indicator">
                        <div class="circle"></div>
                    </div> --}}
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                        <p>Notifications</p>
                        <a href="javascript:;" class="text-muted">Clear all</a>
                    </div>
                    <div class="p-1">
                        <p class="text-muted d-flex align-items-center py-5 px-5">
                            No new notification found.
                        </p>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="wd-30 ht-30 rounded-circle" src="{{ auth()->user()->profile_photo_url }}"
                        alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                        <div class="mb-3">
                            <img class="wd-80 ht-80 rounded-circle" src="{{ auth()->user()->profile_photo_url }}"
                                alt="">
                        </div>
                        <div class="text-center">
                            <p class="tx-16 fw-bolder">{{ auth()->user()->name }}</p>
                            <p class="tx-12 text-muted">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <ul class="list-unstyled p-1">
                        <li class="dropdown-item py-2">

                            <a href="{{ route('user-profile-information.index') }}" class="text-body ms-0 d-block">
                                <i class="me-2 icon-md" data-feather="user"></i>
                                <span>{{___('My Profile') }}</span>
                            </a>
                        </li>
                        <li class="dropdown-item py-2">
                            <a href="{{ route('user-profile-information.edit') }}" class="text-body ms-0 d-block">
                                <i class="me-2 icon-md" data-feather="edit"></i>
                                <span>{{___('Edit Profile') }}</span>
                            </a>
                        </li>
                        <li class="dropdown-item py-2">
                            <a href="{{ route('user-profile-information.general') }}" class="text-body ms-0 d-block">
                                <i class="me-2 icon-md" data-feather="repeat"></i>
                                <span>
                                    {{___('Account Settings') }}
                                </span>
                            </a>
                        </li>
                        <li class="dropdown-item ">
                            <x-logout class="dropdown-item py-2 d-block">
                                <span class="text-black">
                                    <i class="me-2 icon-md" data-feather="log-out"></i>
                                    <span>{{___('Sign Out') }}</span>
                            </x-logout>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
