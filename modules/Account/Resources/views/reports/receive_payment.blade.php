<x-app-layout>
    @include('account::reports.header')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('Receive And Payment') }}</h6>
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
                                <div id="flush-collapseOne" class="accordion-collapse collapse show bg-white mb-2"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="row">
                                        <div class="col-md-3 mb-1">
                                            <label for="date">{{ localize('Date') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control voucher-date-range"
                                                id="voucher-date" autocomplete="off"
                                                placeholder="{{ localize('Voucher Date') }}">
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <label for="type">{{ localize('Ledger Type') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check form-check-inline mr-2">
                                                    <input class="form-check-input" type="radio" name="type"
                                                        id="cash" value="cash" />
                                                    <label class="form-check-label"
                                                        for="cash">{{ localize('Cash') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="type"
                                                        id="bank" value="bank" />
                                                    <label class="form-check-label"
                                                        for="bank">{{ localize('Bank') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2 align-self-end">
                                            <button type="button" id="filter"
                                                class="btn btn-primary me-1">{{ localize('find') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="result">
            </div>
        </div>
    </div>
    @push('lib-styles')
        <link href="{{ admin_asset('vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ admin_asset('vendors/flatpickr/flatpickr.min.js') }}"></script>
    @endpush
    @push('js')
        <script>
            $(function() {
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

                $('#filter').on('click', function(event) {
                    event.preventDefault();
                    if ($('#voucher-date').val() === '' || !$('input[name=type]:checked').val()) {
                        toastr.error('Please select voucher date and ledger type');
                        return false;
                    }
                    let voucher_date = $('#voucher-date').val();
                    let type = $('input[name=type]:checked').val();

                    axios.post('/admin/account/report/receive-payment', {
                        voucher_date: voucher_date,
                        type: type
                    }).then((response) => {
                        console.log('response', response.data);
                        $('#result').html(response.data);
                    }).catch((error) => {
                        toastr.error('Something went wrong');
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
