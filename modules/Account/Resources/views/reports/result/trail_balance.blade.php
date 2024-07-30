<div class="card-body">
    <div class="table-responsive">
        <table class="table display table-bordered table-striped table-hover align-middle">
            <thead class="align-middle">
                <tr>
                    <th rowspan="2">@localize('Code')</th>
                    <th rowspan="2">@localize('Account Name')</th>
                    @if ($type == 'full')
                        <th colspan="2">@localize('Opening Balance')</th>
                        <th colspan="2">@localize('Transaction Balance')</th>
                        <th colspan="2">@localize('Closing Balance')</th>
                    @elseif ($type == 'as_on')
                        <th colspan="2">@localize('Transaction Balance')</th>
                    @else
                        <th colspan="2">@localize('Closing Balance')</th>
                    @endif
                </tr>
                <tr>
                    @if ($type == 'full')
                        <th>@localize('Debit')</th>
                        <th>@localize('Credit')</th>
                        <th>@localize('Debit')</th>
                        <th>@localize('Credit')</th>
                        <th>@localize('Debit')</th>
                        <th>@localize('Credit')</th>
                    @elseif ($type == 'as_on')
                        <th>@localize('Debit')</th>
                        <th>@localize('Credit')</th>
                    @else
                        <th>@localize('Debit')</th>
                        <th>@localize('Credit')</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($trailBalance as $item)
                    <tr>
                        <td>{{ $item['code'] }}</td>
                        <td
                            @if ($item['head_level'] == 1) class="fw-bold" @elseif ($item['head_level'] == 2) class="ps-3" @elseif ($item['head_level'] == 3) class="ps-5" @elseif ($item['head_level'] == 4) class="ps-7" @endif>
                            {{ $item['name'] }}</td>
                        @if ($type == 'full')
                            <td class="text-end">{{ number_format($item['opening_balance_debit'], 2) }}</td>
                            <td class="text-end">{{ number_format($item['opening_balance_credit'], 2) }}</td>
                            <td class="text-end">{{ number_format($item['tran_balance_debit'], 2) }}</td>
                            <td class="text-end">{{ number_format($item['tran_balance_credit'], 2) }}</td>
                            <td class="text-end">{{ number_format($item['closing_balance_debit'], 2) }}</td>
                            <td class="text-end">{{ number_format($item['closing_balance_credit'], 2) }}</td>
                        @elseif ($type == 'as_on')
                            <td class="text-end">{{ number_format($item['tran_balance_debit'], 2) }}</td>
                            <td class="text-end">{{ number_format($item['tran_balance_credit'], 2) }}</td>
                        @else
                            <td class="text-end">{{ number_format($item['closing_balance_debit'], 2) }}</td>
                            <td class="text-end">{{ number_format($item['closing_balance_credit'], 2) }}</td>
                        @endif
                    </tr>
                @empty
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">@localize('Total')</td>
                    @if ($type == 'full')
                        <td class="text-end">{{ number_format($tableFooter['totalOpeningDebitBalance'], 2) }}</td>
                        <td class="text-end">{{ number_format($tableFooter['totalOpeningCreditBalance'], 2) }}</td>
                        <td class="text-end">{{ number_format($tableFooter['totalTransactionDebitBalance'], 2) }}</td>
                        <td class="text-end">{{ number_format($tableFooter['totalTransactionCreditBalance'], 2) }}</td>
                        <td class="text-end">{{ number_format($tableFooter['totalClosingDebitBalance'], 2) }}</td>
                        <td class="text-end">{{ number_format($tableFooter['totalClosingCreditBalance'], 2) }}</td>
                    @elseif ($type == 'as_on')
                        <td class="text-end">{{ number_format($tableFooter['totalTransactionDebitBalance'], 2) }}</td>
                        <td class="text-end">{{ number_format($tableFooter['totalTransactionCreditBalance'], 2) }}</td>
                    @else
                        <td class="text-end">{{ number_format($tableFooter['totalClosingDebitBalance'], 2) }}</td>
                        <td class="text-end">{{ number_format($tableFooter['totalClosingCreditBalance'], 2) }}</td>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
</div>
