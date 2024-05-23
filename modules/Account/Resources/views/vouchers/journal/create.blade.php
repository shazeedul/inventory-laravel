<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="{{ route(config('theme.rprefix') . '.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-list"></i>&nbsp;
                @localize('Journal Voucher')
            </a>
        </x-slot>

        <div>
            <form action="{{ route('admin.account.voucher.journal.store') }}" id="journalVoucherForm" method="post">
                @csrf
                <input type="hidden" id="accounts" value="{{ json_encode($accounts) }}" />
                <input type="hidden" id="subCodeUrl"
                    value="{{ route('admin.account.sub_code.getSubCodesBySubType') }}" />
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="voucher_date" class="col-sm-3 col-form-label">@localize('date')<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="date" name="voucher_date" id="voucher_date" class="form-control"
                                    value="{{ now()->format('Y-m-d') }}" />
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="Remarks" class="col-sm-3 col-form-label">@localize('remarks')</label>
                            <div class="col-lg-9">
                                <textarea name="remarks" class="form-control" id="remarks" rows="3" placeholder="@localize('remarks')"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover" id="journalAccVoucher">
                    <thead>
                        <tr>
                            <th width="25%" class="text-center">@localize('account_name')</th>
                            <th width="25%" class="text-center">@localize('subtype')</th>
                            <th width="15%" class="text-center">@localize('ledger_comment')</th>
                            <th width="15%" class="text-center">@localize('debit')</th>
                            <th width="15%" class="text-center">@localize('credit')</th>
                            <th width="5%" class="text-center">@localize('action')</th>
                        </tr>
                    </thead>
                    <tbody id="journalVoucher">
                        <tr>
                            <td>
                                <select name="journals[0][coa_id]" class="form-control select2"
                                    onchange="load_subtypeOpen(this)">
                                    <option selected disabled>@localize('select_amount')</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            data-subTypeId="{{ $account->account_sub_type_id }}">{{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="journals[0][sub_code_id]" class="form-control select2" disabled>
                                    <option>@localize('select_subtype')</option>
                                </select>
                                <input type="hidden" name="journals[0][sub_type_id]" value="" />
                            </td>
                            <td>
                                <input type="text" name="journals[0][ledger_comment]" class="form-control text-end"
                                    autocomplete="off" />
                            </td>
                            <td>
                                <input type="number" step="0.01" name="journals[0][debit]" min="0"
                                    class="form-control text-end debit" onkeyup="calculation(this)"
                                    autocomplete="off" />
                            </td>
                            <td>
                                <input type="number" step="0.01" name="journals[0][credit]" min="0"
                                    class="form-control text-end credit" onkeyup="calculation(this)"
                                    autocomplete="off" />
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" type="button" value="Delete"
                                    onclick="deleteRow(this)" autocomplete="off"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select name="journals[1][coa_id]" class="form-control select2"
                                    onchange="load_subtypeOpen(this)">
                                    <option selected disabled>@localize('select_amount')</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            data-subTypeId="{{ $account->account_sub_type_id }}">{{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="journals[1][sub_code_id]" class="form-control select2" disabled>
                                    <option>@localize('select_subtype')</option>
                                </select>
                                <input type="hidden" name="journals[1][sub_type_id]" value="" />
                            </td>
                            <td>
                                <input type="text" name="journals[1][ledger_comment]" class="form-control text-end"
                                    autocomplete="off" />
                            </td>
                            <td>
                                <input type="number" step="0.01" name="journals[1][debit]" min="0"
                                    class="form-control text-end debit" onkeyup="calculation(this)"
                                    autocomplete="off" />
                            </td>
                            <td>
                                <input type="number" step="0.01" name="journals[1][credit]" min="0"
                                    class="form-control text-end credit" onkeyup="calculation(this)"
                                    autocomplete="off" />
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" type="button" value="Delete"
                                    onclick="deleteRow(this)" autocomplete="off"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <button type="button" id="add_more" class="btn btn-primary" onclick="addRow();"
                                    autocomplete="off">@localize('add_more')</button>
                            </td>
                            <td colspan="2" class="text-end">
                                <label for="reason" class="  col-form-label">@localize('total')</label>
                            </td>

                            <td class="text-end">
                                <input type="text" id="grandDebitTotal" class="form-control text-end"
                                    name="grand_debit_total" readonly="readonly" autocomplete="off">
                            </td>
                            <td class="text-end">
                                <input type="text" id="grandCreditTotal" class="form-control text-end"
                                    name="grand_credit_total" readonly="readonly" autocomplete="off">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary m-2 submit_button">@localize('save')</button>
                </div>
            </form>
        </div>
    </x-card>
    @push('lib-styles')
        <link href="{{ nanopkg_asset('vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
    @endpush
    @push('js')
        <script>
            var accounts = JSON.parse($(`#accounts`).val());
            var options = '';
            accounts.forEach(element => {
                options +=
                    `<option value="${element.id}" data-subTypeId="${element.account_sub_type_id}">${element.name}</option>`;
            });
            $(document).ready(function() {
                $(".select2").select2();
            });

            function addRow() {
                var table = $("#journalAccVoucher");
                var row = `<tr>`;
                row += `<td><select name="journals[][coa_id]" class="form-control select2"
                            onchange="load_subtypeOpen(this)">
                            <option selected disabled>` + localize('select_amount') + `</option>`;
                row += options;
                row += `</select></td>`;
                row += `<td><select name="journals[][sub_code_id]" class="form-control select2" disabled><option>` + localize(
                        'select_subtype') +
                    `</option></select><input type="hidden" name="journals[0][sub_type_id]" value="" /></td>`;
                row +=
                    `<td><input type="text" name="journals[][ledger_comment]" class="form-control text-end" autocomplete="off"></td>`;
                row +=
                    `<td><input type="number" step="0.01" name="journals[][debit]" 
                        min="0" class="form-control text-end debit"
                        onkeyup="calculation(this)" autocomplete="off"></td>`;
                row +=
                    `<td><input type="number" step="0.01" name="journals[][credit]" 
                        min="0" class="form-control text-end credit"
                        onkeyup="calculation(this)" autocomplete="off"></td>`;
                row +=
                    `<td> <button class="btn btn-danger btn-sm" type="button" value="Delete"
                        onclick="deleteRow(this)" autocomplete="off"><i class="fa fa-trash"></i></button></td>`;
                row += `</tr>`;
                row += `<td><select name="journals[][coa_id]" class="form-control select2"
                            onchange="load_subtypeOpen(this)">
                            <option selected disabled>` + localize('select_amount') + `</option>`;
                row += options;
                row += `</select></td>`;
                row += `<td><select name="journals[][sub_code_id]" class="form-control select2" disabled><option>` + localize(
                        'select_subtype') +
                    `</option></select><input type="hidden" name="journals[0][sub_type_id]" value="" /></td>`;
                row +=
                    `<td><input type="text" name="journals[][ledger_comment]" class="form-control text-end" autocomplete="off"></td>`;
                row +=
                    `<td><input type="number" step="0.01" name="journals[][debit]" 
                        min="0" class="form-control text-end debit"
                        onkeyup="calculation(this)" autocomplete="off"></td>`;
                row +=
                    `<td><input type="number" step="0.01" name="journals[][credit]" 
                        min="0" class="form-control text-end credit"
                        onkeyup="calculation(this)" autocomplete="off"></td>`;
                row +=
                    `<td> <button class="btn btn-danger btn-sm" type="button" value="Delete"
                        onclick="deleteRow(this)" autocomplete="off"><i class="fa fa-trash"></i></button></td>`;
                row += `</tr>`;
                $('#journalAccVoucher tbody').append(row);
                arrayAlign('journalAccVoucher');
                $('.select2').select2();
            }

            function deleteRow(e) {
                // Find the closest table element
                var table = $(e).closest('table');
                // Check if there's only one row left in the table body
                if (table.find('tbody tr').length === 1) {
                    // If there's only one row left, don't delete it
                    alert('Cannot delete the last row.');
                    return;
                }
                // Find the parent row element (tr) and remove it
                $(e).closest('tr').remove();
                arrayAlign(table.attr('id'));
                calculation();
            }

            function calculation(element) {
                var $element = $(element);
                var $row = $element.closest('tr');
                var $debitInput = $row.find('.debit');
                var $creditInput = $row.find('.credit');

                if ($element.hasClass('debit') && $debitInput.val()) {
                    $creditInput.val(0);
                } else if ($element.hasClass('credit') && $creditInput.val()) {
                    $debitInput.val(0);
                }
                var totalDebit = 0,
                    totalCredit = 0;
                $('.debit').each(function() {
                    totalDebit += parseFloat($(this).val()) || 0;
                });
                $('.credit').each(function() {
                    totalCredit += parseFloat($(this).val()) || 0;
                });
                $('#grandDebitTotal').val(totalDebit);
                $('#grandCreditTotal').val(totalCredit);
            }

            function arrayAlign(table) {
                var tb = document.getElementById(table);
                var tbody = tb.getElementsByTagName("tbody")[0];
                var rows = tbody.getElementsByTagName('tr');

                for (var i = 0; i < rows.length; i++) {
                    var select = rows[i].querySelectorAll('input, select');
                    select.forEach(function(input) {
                        var name = input.getAttribute('name');
                        var newName = name.replace(/\[\d*\]/, '[' + i + ']');
                        input.setAttribute('name', newName);
                    });
                }
            }

            function load_subtypeOpen(e) {
                var coa_id = e.value;
                var subTypeId = e.options[e.selectedIndex].getAttribute('data-subTypeId');
                var url = $('#subCodeUrl').val();
                if (subTypeId) {
                    axios.post(
                        url, {
                            subType: subTypeId
                        }
                    ).then((response) => {
                        if (response.data.data.length > 0) {
                            var subCodes = response.data.data;
                            var selectSubCode = e.closest('tr').querySelector('select[name*="sub_code_id"]');
                            var selectSubType = e.closest('tr').querySelector('input[name*="sub_type_id"]');
                            selectSubType.value = subTypeId;
                            selectSubCode.innerHTML = '';
                            subCodes.forEach(element => {
                                selectSubCode.innerHTML +=
                                    `<option value="${element.id}">${element.name}</option>`;
                            });
                            selectSubCode.disabled = false;
                            $(".select2").select2();
                        }
                    }).catch((error) => {
                        console.log(error);
                    });
                }
            }

            function checkBankNature(e) {
                var account_head = document.getElementById('account_head');
                var isBankNature = account_head.options[account_head.selectedIndex].getAttribute('data-is-bank-nature');
                if (isBankNature == 1) {
                    document.getElementById('bank_nature').classList.remove('d-none');
                } else {
                    document.getElementById('bank_nature').classList.add('d-none');
                    document.getElementById('cheque_no').value = '';
                    document.getElementById('cheque_date').value = '';
                    document.getElementById('is_honour').checked = false;
                }
            }

            $(function() {
                $('.submit_button').click(function(e) {
                    e.preventDefault();
                    // check if first row debit then last will be credit or first row credit then last  will be debit
                    var firstRowDebit = $('#journalVoucher tr:first-child .debit').val();
                    var lastRowDebit = $('#journalVoucher tr:last-child .debit').val();
                    var firstRowCredit = $('#journalVoucher tr:first-child .credit').val();
                    var lastRowCredit = $('#journalVoucher tr:last-child .credit').val();
                    if (firstRowDebit > 0 && lastRowDebit > 0) {
                        alert('First row debit then last row credit');
                        return false;
                    } else if (firstRowCredit > 0 && lastRowCredit > 0) {
                        alert('First row credit then last row debit');
                        return false;
                    }
                    // check credit row count will be not greater then debit row count
                    var debitRowCount = $('#journalVoucher tr .debit').filter(function() {
                        return $(this).val() > 0;
                    }).length;
                    var creditRowCount = $('#journalVoucher tr .credit').filter(function() {
                        return $(this).val() > 0;
                    }).length;
                    if (debitRowCount < creditRowCount) {
                        alert('Credit row should be not greater then debit row');
                        return false;
                    }

                    // check two credit not set on nearest row
                    var creditRow = $('#journalVoucher tr .credit').filter(function() {
                        return parseFloat($(this).val()) > 0;
                    });
                    var creditRowLength = creditRow.length;
                    var creditRowArray = [];
                    creditRow.each(function() {
                        creditRowArray.push(parseFloat($(this).val()));
                    });
                    for (var i = 0; i < creditRowLength; i++) {
                        if (creditRowArray[i] > 0 && creditRowArray[i + 1] > 0) {
                            alert('Two credit not set on nearest row');
                            return false;
                        }
                    }

                    var grandDebitTotal = $('#grandDebitTotal').val() || 0;
                    var grandCreditTotal = $('#grandCreditTotal').val() || 0;
                    if (grandDebitTotal != grandCreditTotal) {
                        alert('Debit and Credit total not matched');
                        return false;
                    }


                    $('#journalVoucherForm').submit();
                });
            });
        </script>
    @endpush
</x-app-layout>
