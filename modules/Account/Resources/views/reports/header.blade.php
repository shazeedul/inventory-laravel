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
        @can('day_book_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.day-book') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.day-book') }}">{{ localize('Day Book') }}</a>
            </li>
        @endcan
        @can('general_ledger_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.general-ledger') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.general-ledger') }}">{{ localize('General Ledger') }}</a>
            </li>
        @endcan
        @can('sub_ledger_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.sub-ledger') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.sub-ledger') }}">{{ localize('Sub Ledger') }}</a>
            </li>
        @endcan
        @can('control_ledger_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.control-ledger') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.control-ledger') }}">{{ localize('Control Ledger') }}</a>
            </li>
        @endcan
        @can('note_ledger_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.note-ledger') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.note-ledger') }}">{{ localize('Note Ledger') }}</a>
            </li>
        @endcan
        @can('receive_payment_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.receive-payment') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.receive-payment') }}">{{ localize('Receive And Payment') }}</a>
            </li>
        @endcan
        @can('trail_balance_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.trail-balance') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.trail-balance') }}">{{ localize('Trail Balance') }}</a>
            </li>
        @endcan
        @can('profit_loss_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.profit-loss') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.profit-loss') }}">{{ localize('Profit Loss') }}</a>
            </li>
        @endcan
        @can('balance_sheet_report')
            <li class="nav-item">
                <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.report.balance-sheet') ? 'active' : '' }}"
                    href="{{ route('admin.account.report.balance-sheet') }}">{{ localize('Balance Sheet') }}</a>
            </li>
        @endcan
    </ul>
</nav>
