<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="{{ route(config('theme.rprefix') . '.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-list"></i>&nbsp;
                @localize('Contra Voucher')
            </a>
        </x-slot>

        <div>
            <form action="{{ route('admin.account.voucher.contra.store') }}" method="post">
                @csrf
                <input type="hidden" id="accounts" value="{{ json_encode($accounts) }}" />
                <input type="hidden" id="subCodeUrl"
                    value="{{ route('admin.account.sub_code.getSubCodesBySubType') }}" />
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="account_head" class="col-sm-3 col-form-label">@localize('account_head')<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="account_head" id="account_head" onchange="checkBankNature()"
                                    class="form-select select2">
                                    <option>@localize('select_one')</option>
                                    @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}"
                                            data-is-bank-nature="{{ $item->is_bank_nature }}">{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="bank_nature" class="d-none">
                            <div class="form-group mb-2 mx-0 row">
                                <label for="cheque_no" class="col-sm-3 col-form-label">@localize('cheque_no')</label>
                                <div class="col-lg-9">
                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control"
                                        placeholder="@localize('cheque_no')" />
                                </div>
                            </div>
                            <div class="form-group mb-2 mx-0 row">
                                <label for="cheque_date" class="col-sm-3 col-form-label">@localize('cheque_date')</label>
                                <div class="col-lg-9">
                                    <input type="date" name="cheque_date" id="cheque_date" class="form-control"
                                        placeholder="@localize('cheque_date')" />
                                </div>
                            </div>
                            <div class="form-group mb-2 mx-0 row">
                                <label for="is_honour" class="col-sm-3 col-form-label">@localize('is_honour')</label>
                                <div class="col-lg-9">
                                    <input type="checkbox" name="is_honour" id="is_honour" class="form-check-input"
                                        placeholder="@localize('is_honour')" value="1" />
                                </div>
                            </div>
                        </div>
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
                <table class="table table-bordered table-hover" id="creditAccVoucher">
                    <thead>
                        <tr>
                            <th width="25%" class="text-center">@localize('account_name')</th>
                            <th width="20%" class="text-center">@localize('ledger_comment')</th>
                            <th width="20%" class="text-center">@localize('debit')</th>
                            <th width="20%" class="text-center">@localize('credit')</th>
                        </tr>
                    </thead>
                    <tbody id="creditVoucher">
                        <tr>
                            <td>
                                <select name="contra[0][coa_id]" class="form-control select2">
                                    <option selected disabled>@localize('select_amount')</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="contra[0][ledger_comment]" class="form-control text-end"
                                    autocomplete="off" />
                            </td>
                            <td>
                                <input type="number" step="0.01" name="contra[0][debit]" min="1"
                                    class="form-control text-end debitAmount" onkeyup="calculation()"
                                    autocomplete="off" />
                            </td>
                            <td>
                                <input type="number" step="0.01" name="contra[0][credit]" min="1"
                                    class="form-control text-end creditAmount" onkeyup="calculation()"
                                    autocomplete="off" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary m-2 submit_button"
                        id="create_submit">@localize('save')</button>
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

            function calculation() {
                var totalDebit = 0;
                var totalCredit = 0;
                $('.debitAmount').each(function() {
                    totalDebit += parseFloat($(this).val()) || 0;
                });
                $('.creditAmount').each(function() {
                    totalCredit += parseFloat($(this).val()) || 0;
                });
                $('#grandTotalDebit').val(totalDebit);
                $('#grandTotalCredit').val(totalCredit);
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
        </script>
    @endpush
</x-app-layout>
