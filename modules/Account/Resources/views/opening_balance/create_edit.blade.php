<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="{{ route(config('theme.rprefix') . '.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-list"></i>&nbsp;
                @localize('Opening Balance')
            </a>
        </x-slot>
        <form
            action="{{ isset($data) ? route(config('theme.rprefix') . '.update', $data->id) : route(config('theme.rprefix') . '.store') }}"
            method="post">
            @csrf
            @if (isset($data))
                @method('PUT')
            @endif
            {{-- <input type="hidden" id="accountsJson" value="{{ json_encode($accounts) }}" /> --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2 mx-0 row">
                        <label for="financial_year_id" class="col-sm-3 col-form-label ps-0">@localize('Financial Year')
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <select name="financial_year_id" id="financial_year_id" class="form-control">
                                <option value="">@localize('Select Financial Year')</option>
                                @foreach ($financial_years as $year)
                                    <option value="{{ $year->id }}"
                                        @isset($data)
                                                @if ($data->financial_year_id == $year->id) selected @endif
                                            @endisset>
                                        {{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-2 mx-0 row">
                        <label for="opening_date" class="col-sm-3 col-form-label ps-0">@localize('Opening Date')
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <input type="date" name="opening_date" id="opening_date" class="form-control"
                                @isset($data)
                                                value="{{ $data->opening_date }}"
                                            @endisset />
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <table class="table table-bordered table-hover" id="opening-balances-table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">@localize('Account Name')</th>
                            <th scope="col" class="text-center">@localize('Sub Code')</th>
                            <th scope="col" class="text-center">@localize('Debit')</th>
                            <th scope="col" class="text-center">@localize('Credit')</th>
                            <th scope="col" class="text-center">@localize('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="chart_of_account_id[]" class="form-control chart_of_account select2"
                                    onchange="coaSet(this)">
                                    <option value="">@localize('Select Account')</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            data-subType="{{ $account->account_sub_type_id }}"
                                            @isset($data)
                                                @selected($data->chart_of_account_id == $account->id)
                                            @endisset>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="account_sub_type_id[]"
                                    value="{{ !empty($data) ? $data->account_sub_type_id : '' }}" />
                                <input type="hidden" name="edit_id[]" value="{{ !empty($data) ? $data->id : '' }}" />

                            </td>
                            <td>
                                <select name="account_sub_code_id[]" class="form-control select2"
                                    @isset($data)
                                        @disabled(!$data->account_sub_type_id)
                                    @endisset>
                                    <option value="">@localize('Select Sub Code')</option>
                                    @isset($data)
                                        @foreach ($subCodes as $item)
                                            <option value="{{ $item->id }}" @selected($data->account_sub_code_id == $item->id)>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </td>
                            <td>
                                <input type="number" name="debit[]" class="form-control debit text-end" min="0"
                                    step="0.01" onkeyup="calculateSum()"
                                    value="{{ !empty($data) ? $data->debit : '0.00' }}" />
                            </td>
                            <td>
                                <input type="number" name="credit[]" class="form-control credit text-end"
                                    min="0" step="0.01" onkeyup="calculateSum()"
                                    value="{{ !empty($data) ? $data->credit : '0.00' }}" />
                            </td>
                            <td width="1%">
                                @if (empty($data))
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i
                                            class="fa fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm add-row" onclick="addRow()">
                                    <i class="fa fa-plus"></i>&nbsp;@localize('Add')
                                </button>
                            </td>
                            <td class="text-end">@localize('Total')</td>
                            <td><input type="number" class="form-control total-debit-amount text-end" value="0"
                                    readonly />
                            </td>
                            <td><input type="number" class="form-control total-credit-amount text-end" value="0"
                                    readonly /></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <div class="row text-end">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">@localize('Save')</button>
                </div>
            </div>
        </form>
    </x-card>
    @push('lib-styles')
        <link href="{{ nanopkg_asset('vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
    @endpush
    @push('js')
        <script>
            $(document).ready(function() {
                calculateSum();
                $(".select2").select2();
            });

            function coaSet(e) {
                var subType = $(e).find(':selected').data('subtype');
                $(e).closest('tr').find('input[name="account_sub_type_id[]"]').val(subType);
                if (subType) {
                    $(e).closest('tr').find('select[name="account_sub_code_id[]"]').prop('disabled',
                        false);
                    $(e).closest('tr').find('select[name="account_sub_code_id[]"]').prop('required',
                        true);
                    axios
                        .post("{{ route('admin.account.sub_code.getSubCodesBySubType') }}", {
                            subType: subType,
                        })
                        .then((res) => {
                            if (res.data.data.length > 0) {
                                var subCodes = $(e).closest('tr').find('select[name="account_sub_code_id[]"]');
                                subCodes.empty();
                                subCodes.append(
                                    '<option value="">@localize('Select Sub Code')</option>'
                                );
                                res.data.data.forEach((subCode) => {
                                    subCodes.append(
                                        '<option value="' + subCode.id + '">' + subCode.name + '</option>'
                                    );
                                });
                            }
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                } else {
                    $(e).closest('tr').find('select[name="account_sub_code_id[]"]').prop('disabled',
                        true);
                }
            }

            function removeRow(e) {
                $(e).closest('tr').remove();
                calculateSum();
            }

            function calculateSum() {
                var totalDebitAmount = 0;
                var totalCreditAmount = 0;
                $('.debit').each(function() {
                    totalDebitAmount += parseFloat($(this).val());
                });
                $('.credit').each(function() {
                    totalCreditAmount += parseFloat($(this).val());
                });
                $('.total-debit-amount').val(totalDebitAmount);
                $('.total-credit-amount').val(totalCreditAmount);
            }


            function addRow() {
                var html = '';
                html += '<tr>';
                html += '<td>';
                html +=
                    '<select name="chart_of_account_id[]" class="form-control chart_of_account select2" onchange="coaSet(this)">';
                html += '<option value="">@localize('Select Account')</option>';
                @foreach ($accounts as $account)
                    html +=
                        '<option value="{{ $account->id }}" data-subtype="{{ $account->account_sub_type_id }}">{{ $account->name }}</option>';
                @endforeach
                html += '</select>';
                html += '<input type="hidden" name="account_sub_type_id[]" value="" />';
                html += '<input type="hidden" name="edit_id[]" value="" />';
                html += '</td>';
                html += '<td>';
                html += '<select name="account_sub_code_id[]" class="form-control select2">';
                html += '<option value="">@localize('Select Sub Code')</option>';
                html += '</select>';
                html += '</td>';
                html += '<td>';
                html +=
                    '<input type="number" name="debit[]" class="form-control debit text-end" onkeyup="calculateSum()" value="0.00" min="0" step="0.01" />';
                html += '</td>';
                html += '<td>';
                html +=
                    '<input type="number" name="credit[]" class="form-control credit text-end" onkeyup="calculateSum()" value="0.00" min="0" step="0.01" />';
                html += '</td>';
                html += '<td>';
                html +=
                    '<button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>';
                html += '</td>';
                html += '</tr>';
                $('#opening-balances-table tbody').append(html);
                $('.select2').select2();
            };
        </script>
    @endpush
</x-app-layout>
