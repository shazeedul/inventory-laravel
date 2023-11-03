<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <img src="{{ setting('site.logo_dark', admin_asset('images/logo.png'), true) }}" alt="">
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul id="left-side-nav" class="nav ">

            <x-admin.nav-title title="Main" />
            <x-admin.nav-link href="{{ route('admin.dashboard') }}">
                <i class="link-icon" data-feather="box"></i>
                <span class="link-title">
                    @localize('Dashboard')
                </span>
            </x-admin.nav-link>
            {{-- Supplier --}}
            @if (module_active('supplier') || can('supplier_management'))
                <x-admin.nav-title title="Supplier" />
                <x-admin.multi-nav>
                    @slot('title')
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">
                            @localize('Supplier')
                        </span>
                    @endslot
                    <x-admin.nav-link href="{{ route('admin.supplier.index') }}">
                        @localize('All Supplier')
                    </x-admin.nav-link>
                </x-admin.multi-nav>
            @endif
            {{-- Customer --}}
            @if (module_active('customer') || can('customer_management'))
                <x-admin.nav-title title="Customer" />
                <x-admin.multi-nav>
                    @slot('title')
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">
                            @localize('Customer')
                        </span>
                    @endslot
                    <x-admin.nav-link href="{{ route('admin.customer.index') }}">
                        @localize('All Customer')
                    </x-admin.nav-link>
                </x-admin.multi-nav>
            @endif
            {{-- Unit --}}
            @if (module_active('unit') || can('unit_management'))
                <x-admin.nav-title title="Unit" />
                <x-admin.multi-nav>
                    @slot('title')
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">
                            @localize('Unit')
                        </span>
                    @endslot
                    <x-admin.nav-link href="{{ route('admin.unit.index') }}">
                        @localize('All Unit')
                    </x-admin.nav-link>
                </x-admin.multi-nav>
            @endif
            {{-- Category --}}
            @if (module_active('category') || can('category_management'))
                <x-admin.nav-title title="Category" />
                <x-admin.multi-nav>
                    @slot('title')
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">
                            @localize('Category')
                        </span>
                    @endslot
                    <x-admin.nav-link href="{{ route('admin.category.index') }}">
                        @localize('All Category')
                    </x-admin.nav-link>
                </x-admin.multi-nav>
            @endif
            {{-- Product --}}
            @if (module_active('product') || can('product_management'))
                <x-admin.nav-title title="Product" />
                <x-admin.multi-nav>
                    @slot('title')
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">
                            @localize('Product')
                        </span>
                    @endslot
                    <x-admin.nav-link href="{{ route('admin.product.index') }}">
                        @localize('All Product')
                    </x-admin.nav-link>
                </x-admin.multi-nav>
            @endif
            @if (can('permission_management') ||
                    can('role_read') ||
                    can('user_management') ||
                    (module_active('setting') && can('setting_management')))
                <x-admin.nav-title title="Settings" />
                <x-admin.multi-nav>
                    @slot('title')
                        <i class="link-icon" data-feather="settings"></i>
                        <span class="link-title">
                            @localize('Setting')
                        </span>
                    @endslot
                    @if (module_active('setting') && can('setting_management'))
                        <x-admin.nav-link href="{{ route('admin.setting.index') }}">
                            @localize('General Setting')
                        </x-admin.nav-link>
                    @endif
                    {{-- User Management --}}
                    @if (module_active('permission') && can('permission_management'))
                        <x-admin.nav-link href="{{ route('admin.permission.index') }}">
                            @localize('Permission')
                        </x-admin.nav-link>
                    @endif
                    {{-- Role --}}
                    @if (module_active('role') && can('role_management'))
                        <x-admin.nav-link href="{{ route('admin.role.index') }}">
                            @localize('Role')
                        </x-admin.nav-link>
                    @endif
                    {{-- User --}}
                    @if (module_active('user') && can('user_management'))
                        <x-admin.nav-link href="{{ route('admin.user.index') }}">
                            @localize('User')
                        </x-admin.nav-link>
                    @endif
                </x-admin.multi-nav>
            @endif
            {{-- Backup --}}
            @if (module_active('backup') && can('backup_management'))
                <x-admin.nav-title title="Backup" />
                <x-admin.nav-link href="{{ route('admin.backup.index') }}">
                    <i class="link-icon" data-feather="download-cloud"></i>
                    <span class="link-title">
                        @localize('Backup')
                    </span>
                </x-admin.nav-link>
            @endif
    </div>
</nav>
