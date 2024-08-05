<div class="card-body">
    <div class="table-responsive">
        <table class="table display table-bordered table-striped table-hover align-middle">
            <thead class="align-middle">
                <tr>
                    <th>@localize('Particulars')</th>
                    <th>@localize('Amount')</th>
                    <th>@localize('Amount')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>@localize('Income')</td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($incomes as $level_two_income)
                    <tr>
                        <td>{{ $level_two_income->name }}</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ $level_two_income->balance }}</td>
                    </tr>
                    @foreach ($level_two_income->thirdChild as $level_three_income)
                        <tr>
                            <td style="padding-left:100px;">{{ $level_three_income->name }}</td>
                            <td class="text-end">
                                {{ $level_three_income->fourthChild->sum('balance') }}
                            </td>
                            <td class="text-end"></td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <th class="text-end"></th>
                    <th class="text-end">@localize('Total Income')</th>
                    <th class="text-end">{{ $incomeBalance }}</th>
                </tr>
                <tr>
                    <td>@localize('Expense')</td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($expenses as $level_two_expense)
                    <tr>
                        <td>{{ $level_two_expense->name }}</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ $level_two_expense->balance }}</td>
                    </tr>
                    @foreach ($level_two_expense->thirdChild as $level_three_expense)
                        <tr>
                            <td style="padding-left:100px;">{{ $level_three_expense->name }}</td>
                            <td class="text-end">{{ $level_three_expense->fourthChild->sum('balance') }}</td>
                            <td class="text-end"></td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <th class="text-end"></th>
                    <th class="text-end">@localize('Total Expense')</th>
                    <th class="text-end">{{ $expenseBalance }}</th>
                </tr>
            </tbody>
            <tfoot>
                @if ($netProfit >= 0)
                    <tr>
                        <th class="text-end">@localize('Net Profit')</th>
                        <th class="text-end"> {{ $netProfit }}</th>
                        <th></th>
                    </tr>
                @endif
                <tr>
                    <th class="text-end"></th>
                    <th class="text-end">@localize('Total')</th>
                    <th class="text-end">{{ $netProfit > 0 ? $netProfit : $netLoss }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
