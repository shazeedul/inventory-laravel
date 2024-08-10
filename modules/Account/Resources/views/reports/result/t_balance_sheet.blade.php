<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table display table-bordered table-striped table-hover align-middle">
                    <thead class="align-middle">
                        <tr>
                            <th><strong>@localize('Particulars')</strong></th>
                            <th>
                                <strong>
                                    {{ $currentYear->name }}
                                </strong>
                            </th>
                            @foreach ($lastThreeYears as $year)
                                <th><strong>{{ $year->name }}</strong></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>@localize('Liabilities')</strong></td>
                            <td></td>
                            @foreach ($lastThreeYears as $year)
                                <td></td>
                            @endforeach
                        </tr>
                        @foreach ($liabilities as $liability2)
                            <tr>
                                <td>{{ $liability2->name }}</td>
                                <td>{{ $liability2->balance }}</td>
                                @foreach ($lastThreeYears as $year)
                                    <td>
                                        {{ number_format($liability2->{"year_balance_{$year->name}"}) }}
                                    </td>
                                @endforeach
                            </tr>
                            @foreach ($liability2->thirdChild as $liability3)
                                <tr>
                                    <td>{{ $liability3->name }}</td>
                                    <td>{{ $liability3->balance }}</td>
                                    @foreach ($lastThreeYears as $year)
                                        <td>
                                            {{ number_format($liability3->{"year_balance_{$year->name}"}) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @foreach ($liability3->fourthChild as $liability4)
                                    <tr>
                                        <td>{{ $liability4->name }}</td>
                                        <td>{{ $liability4->balance }}</td>
                                        @foreach ($lastThreeYears as $year)
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
                            <td><strong>{{ $liabilityBalance }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
