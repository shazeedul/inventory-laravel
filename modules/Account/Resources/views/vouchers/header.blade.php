@push('css')
    <style>
        .vouchers-nav.card {
            border-right: 1px solid #e9ecef;
        }

        .vouchers-nav.nav-tabs {
            border-bottom: transparent !important;
        }

        .vouchers-nav ul li {
            padding: 5px 15px;
            position: relative;
            white-space: nowrap;
        }

        .vouchers-nav .nav-item .nav-link {
            border-color: transparent !important;
            background-color: transparent !important;
        }
    </style>
@endpush
<nav class="card py-2">
    <ul class="nav nav-tabs vouchers-nav">
        {{-- @can('read_debit_voucher') --}}
        <li class="nav-item">
            <a class="nav-link py-1 pl-0 {{ request()->routeIs('admin.account.voucher.debit.index') || request()->routeIs('admin.account.voucher.debit.create') || request()->routeIs('admin.account.voucher.debit.edit') ? 'active' : '' }}"
                href="{{ route('admin.account.voucher.debit.index') }}">{{ localize('debit_voucher') }}</a>
        </li>
        {{-- @endcan --}}
        {{-- @can('read_credit_voucher') --}}
        <li class="nav-item">
            <a class="nav-link mt-0 py-1  {{ request()->routeIs('admin.account.voucher.credit.index') || request()->routeIs('admin.account.voucher.credit.create') || request()->routeIs('admin.account.voucher.credit.edit') ? 'active' : '' }}"
                href="{{ route('admin.account.voucher.credit.index') }}">{{ localize('credit_voucher') }}</a>
        </li>
        {{-- @endcan --}}
        {{-- @can('read_contra_voucher') --}}
        <li class="nav-item">
            <a class="nav-link mt-0 py-1  {{ request()->routeIs('admin.account.voucher.contra.index') || request()->routeIs('admin.account.voucher.contra.create') || request()->routeIs('admin.account.voucher.contra.edit') ? 'active' : '' }}"
                href="{{ route('admin.account.voucher.contra.index') }}">{{ localize('contra_voucher') }}</a>
        </li>
        {{-- @endcan --}}
        {{-- @can('read_journal_voucher') --}}
        <li class="nav-item">
            <a class="nav-link mt-0 py-1 {{ request()->routeIs('admin.account.voucher.journal.index') || request()->routeIs('admin.account.voucher.journal.create') || request()->routeIs('admin.account.voucher.journal.edit') ? 'active' : '' }}"
                href="{{ route('admin.account.voucher.journal.index') }}">{{ localize('journal_voucher') }}</a>
        </li>
        {{-- @endcan --}}
    </ul>
</nav>
