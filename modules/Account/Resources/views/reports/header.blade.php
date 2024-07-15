@push('css')
    <style>
        .reports-nav.card {
            border-right: 1px solid #e9ecef;
        }

        .reports-nav.nav-tabs {
            border-bottom: transparent !important;
        }

        .reports-nav ul li {
            padding: 5px 15px;
            position: relative;
            white-space: nowrap;
        }

        .reports-nav .nav-item .nav-link {
            border-color: transparent !important;
            background-color: transparent !important;
        }
    </style>
@endpush
<nav class="card py-2 my-2">
    <ul class="nav nav-tabs reports-nav">
        @can('cash_book_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.cash-book') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.cash-book') }}">{{ localize('Cash Book') }}</a>
            </li>
        @endcan
        @can('bank_book_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.bank-book') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.bank-book') }}">{{ localize('Bank Book') }}</a>
            </li>
        @endcan
    </ul>
</nav>
