<div class="card-body">
    <div class="row">
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

                </tbody>
            </table>
        </div>
    </div>
</div>
