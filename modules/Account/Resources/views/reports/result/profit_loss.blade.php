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
                    @empty
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center">@localize('Total')</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
