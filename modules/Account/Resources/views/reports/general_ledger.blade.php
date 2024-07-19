<x-app-layout>
    @include('account::reports.header')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('General Ledger') }}</h6>
                        </div>

                        <div class="text-end">
                            <div class="actions">
                                <button type="button" class="btn btn-primary" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne"> <i class="fas fa-filter"></i>
                                    {{ localize('filter') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row col-12">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-2"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <select id="accounts" class="form-select">
                                                <option value="" selected>
                                                    {{ localize('Ledger Name') }}</option>
                                                @foreach ($accounts as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <input type="text" class="form-control voucher-date-range"
                                                id="voucher-date" autocomplete="off"
                                                placeholder="{{ localize('Voucher Date') }}">
                                        </div>
                                        <div class="col-md-2 mb-2 align-self-end">
                                            <button type="button" id="filter"
                                                class="btn btn-primary me-1">{{ localize('find') }}</button>
                                            <button type="button" id="reset"
                                                class="btn btn-danger">{{ localize('reset') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <x-data-table :dataTable="$dataTable" />
                </div>
            </div>
        </div>
    </div>
    <div id="page-axios-data" data-table-id="#generalLedger-table"></div>
    @push('lib-styles')
        <link href="{{ nanopkg_asset('vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ admin_asset('vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
        <script src="{{ admin_asset('vendors/flatpickr/flatpickr.min.js') }}"></script>
    @endpush
    @push('js')
        <script>
            $(function() {
                var table = $('#generalLedger-table');
                $('#accounts').select2({
                    placeholder: "Select Ledger"
                });

                $('#voucher-date').flatpickr({
                    mode: "range",
                    maxDate: "today",
                    dateFormat: "Y-m-d",
                    locale: {
                        firstDayOfWeek: 1,
                        weekdays: {
                            shorthand: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                            longhand: [
                                'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                                'Saturday'
                            ],
                        },
                        months: {
                            shorthand: [
                                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                                'Nov', 'Dec'
                            ],
                            longhand: [
                                'January', 'February', 'March', 'April', 'May', 'June', 'July',
                                'August', 'September', 'October', 'November', 'December'
                            ],
                        },
                    },
                });

                $('#filter').on('click', function() {
                    let chart_of_account_id = $('#accounts').val();
                    let voucher_date = $('#voucher-date').val();
                    table.on('preXhr.dt', function(e, settings, data) {
                        data.chart_of_account_id = chart_of_account_id;
                        data.voucher_date = voucher_date;
                    });
                    table.DataTable().ajax.reload();
                });

                $('#reset').on('click', function() {
                    $('#accounts').val('').trigger('change');
                    $('#voucher-date').flatpickr().clear();
                    $('#voucher-date').flatpickr({
                        mode: "range",
                        maxDate: "today",
                        dateFormat: "Y-m-d",
                        locale: {
                            firstDayOfWeek: 1,
                            weekdays: {
                                shorthand: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                                longhand: [
                                    'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday',
                                    'Friday',
                                    'Saturday'
                                ],
                            },
                            months: {
                                shorthand: [
                                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                                    'Oct',
                                    'Nov', 'Dec'
                                ],
                                longhand: [
                                    'January', 'February', 'March', 'April', 'May', 'June', 'July',
                                    'August', 'September', 'October', 'November', 'December'
                                ],
                            },
                        },
                    });
                    table.on('preXhr.dt', function(e, settings, data) {
                        data.chart_of_account_id = '';
                        data.voucher_date = '';
                    });

                    table.DataTable().ajax.reload();
                });
            });
        </script>
    @endpush
</x-app-layout>
