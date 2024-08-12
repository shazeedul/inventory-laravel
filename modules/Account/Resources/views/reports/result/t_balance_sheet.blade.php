<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table display table-bordered table-striped table-hover align-middle">
                    <thead class="align-middle">
                        <tr>
                            <th><strong>@localize('Particulars')</strong></th>
                            <th colspan="2">
                                <strong>
                                    {{ $currentYear->name }}
                                </strong>
                            </th>
                            @foreach ($lastThreeYears as $year)
                                <th colspan="2"><strong>{{ $year->name }}</strong></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>@localize('Liabilities')</strong></td>
                            <td><strong>@localize('Debit')</strong></td>
                            <td><strong>@localize('Credit')</strong></td>
                            @foreach ($lastThreeYears as $year)
                                <td><strong>@localize('Debit')</strong></td>
                                <td><strong>@localize('Credit')</strong></td>
                            @endforeach
                        </tr>
                        @foreach ($liabilities as $liability2)
                            <tr>
                                <td>{{ $liability2->name }}</td>
                                <td>0.00</td>
                                <td>{{ $liability2->balance }}</td>
                                @foreach ($lastThreeYears as $year)
                                    <td>0.00</td>
                                    <td>
                                        {{ number_format($liability2->{"year_balance_{$year->name}"}) }}
                                    </td>
                                @endforeach
                            </tr>
                            @foreach ($liability2->thirdChild as $liability3)
                                <tr>
                                    <td>{{ $liability3->name }}</td>
                                    <td>0.00</td>
                                    <td>{{ $liability3->balance }}</td>
                                    @foreach ($lastThreeYears as $year)
                                        <td>0.00</td>
                                        <td>
                                            {{ number_format($liability3->{"year_balance_{$year->name}"}) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @foreach ($liability3->fourthChild as $liability4)
                                    <tr>
                                        <td>{{ $liability4->name }}</td>
                                        <td>0.00</td>
                                        <td>{{ $liability4->balance }}</td>
                                        @foreach ($lastThreeYears as $year)
                                            <td>0.00</td>
                                            <td>
                                                {{ number_format($liability4->{"year_balance_{$year->name}"}) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>@localize('Total Liabilities')</strong></td>
                            <td>0.00</td>
                            <td><strong>{{ $liabilityBalance }}</strong></td>
                            @foreach ($lastThreeYears as $year)
                                <td>0.00</td>
                                <td><strong>{{ $liabilities->sum('year_balance_' . $year->name) }}</strong></td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table display table-bordered table-striped table-hover align-middle">
                    <thead class="align-middle">
                        <tr>
                            <th><strong>@localize('Particulars')</strong></th>
                            <th colspan="2">
                                <strong>
                                    {{ $currentYear->name }}
                                </strong>
                            </th>
                            @foreach ($lastThreeYears as $year)
                                <th colspan="2"><strong>{{ $year->name }}</strong></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>@localize('Share Equity')</strong></td>
                            <td><strong>@localize('Debit')</strong></td>
                            <td><strong>@localize('Credit')</strong></td>
                            @foreach ($lastThreeYears as $year)
                                <td><strong>@localize('Debit')</strong></td>
                                <td><strong>@localize('Credit')</strong></td>
                            @endforeach
                        </tr>
                        @foreach ($shareEquities as $shareEquity2)
                            <tr>
                                <td>{{ $shareEquity2->name }}</td>
                                <td>0.00</td>
                                <td>{{ $shareEquity2->balance }}</td>
                                @foreach ($lastThreeYears as $year)
                                    <td>0.00</td>
                                    <td>
                                        {{ number_format($shareEquity2->{"year_balance_{$year->name}"}) }}
                                    </td>
                                @endforeach
                            </tr>
                            @foreach ($shareEquity2->thirdChild as $shareEquity3)
                                <tr>
                                    <td>{{ $shareEquity3->name }}</td>
                                    <td>0.00</td>
                                    <td>{{ $shareEquity3->balance }}</td>
                                    @foreach ($lastThreeYears as $year)
                                        <td>0.00</td>
                                        <td>
                                            {{ number_format($shareEquity3->{"year_balance_{$year->name}"}) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @foreach ($shareEquity3->fourthChild as $shareEquity4)
                                    <tr>
                                        <td>{{ $shareEquity4->name }}</td>
                                        <td>0.00</td>
                                        <td>{{ $shareEquity4->balance }}</td>
                                        @foreach ($lastThreeYears as $year)
                                            <td>0.00</td>
                                            <td>
                                                {{ number_format($shareEquity4->{"year_balance_{$year->name}"}) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>@localize('Total Share Equity')</strong></td>
                            <td>0.00</td>
                            <td><strong>{{ $shareEquityBalance }}</strong></td>
                            @foreach ($lastThreeYears as $year)
                                <td>0.00</td>
                                <td><strong>{{ $shareEquities->sum('year_balance_' . $year->name) }}</strong></td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table display table-bordered table-striped table-hover align-middle">
                    <thead class="align-middle">
                        <tr>
                            <th><strong>@localize('Particulars')</strong></th>
                            <th colspan="2">
                                <strong>
                                    {{ $currentYear->name }}
                                </strong>
                            </th>
                            @foreach ($lastThreeYears as $year)
                                <th colspan="2"><strong>{{ $year->name }}</strong></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>@localize('Assets')</strong></td>
                            <td><strong>@localize('Debit')</strong></td>
                            <td><strong>@localize('Credit')</strong></td>
                            @foreach ($lastThreeYears as $year)
                                <td><strong>@localize('Debit')</strong></td>
                                <td><strong>@localize('Credit')</strong></td>
                            @endforeach
                        </tr>
                        @foreach ($assets as $asset2)
                            <tr>
                                <td>{{ $asset2->name }}</td>
                                <td>{{ $asset2->balance }}</td>
                                <td>0.00</td>
                                @foreach ($lastThreeYears as $year)
                                    <td>
                                        {{ number_format($asset2->{"year_balance_{$year->name}"}) }}
                                    </td>
                                    <td>0.00</td>
                                @endforeach
                            </tr>
                            @foreach ($asset2->thirdChild as $asset3)
                                <tr>
                                    <td>{{ $asset3->name }}</td>
                                    <td>{{ $asset3->balance }}</td>
                                    <td>0.00</td>
                                    @foreach ($lastThreeYears as $year)
                                        <td>
                                            {{ number_format($asset3->{"year_balance_{$year->name}"}) }}
                                        </td>
                                        <td>0.00</td>
                                    @endforeach
                                </tr>
                                @foreach ($asset3->fourthChild as $asset4)
                                    <tr>
                                        <td>{{ $asset4->name }}</td>
                                        <td>{{ $asset4->balance }}</td>
                                        <td>0.00</td>
                                        @foreach ($lastThreeYears as $year)
                                            <td>
                                                {{ number_format($asset4->{"year_balance_{$year->name}"}) }}
                                            </td>
                                            <td>0.00</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>@localize('Total Assets')</strong></td>
                            <td><strong>{{ $assetBalance }}</strong></td>
                            <td>0.00</td>
                            @foreach ($lastThreeYears as $year)
                                <td><strong>{{ $assets->sum('year_balance_' . $year->name) }}</strong></td>
                                <td>0.00</td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
