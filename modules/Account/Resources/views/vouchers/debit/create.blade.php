<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="{{ route(config('theme.rprefix') . '.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-list"></i>&nbsp;
                @localize('Debit Voucher')
            </a>
        </x-slot>

        <div>
            <form action="#" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="account_head" class="col-sm-3 col-form-label">@localize('account_head')<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="account_head" id="account_head" class="form-select select2">
                                    <option value="">@localize('select_one')</option>
                                    @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
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
                <table class="table table-bordered table-hover" id="debtAccVoucher">
                    <thead>
                        <tr>
                            <th width="25%" class="text-center">@localize('account_name')</th>
                            <th width="25%" class="text-center">@localize('subtype')</th>
                            <th width="20%" class="text-center">@localize('ledger_comment')</th>
                            <th width="20%" class="text-center">@localize('amount')</th>
                            <th width="10%" class="text-center">@localize('action')</th>
                        </tr>
                    </thead>
                    <tbody id="debitVoucher">
                        <tr>
                            <td>
                                <select name="debits[1][coa_id]" id="code_1" class="form-control select2"
                                    onchange="load_subtypeOpen(this.value,1)">
                                    <option selected disabled>@localize('select_amount')</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="debits[1][sub_code_id]" id="subtype_1" class="form-control" disabled>
                                    <option>@localize('select_subtype')</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="debits[1][ledger_comment]" value=""
                                    class="form-control text-end" id="ledger_comment_1" autocomplete="off">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="debits[1][amount]" value=""
                                    min="1" class="form-control text-end" id="txtCredit_1"
                                    onkeyup="calculationCreditOpen(1)" autocomplete="off">
                                <input type="hidden" step="0.01" name="debits[1][is_subtype]" id="isSubtype_1"
                                    value="1" autocomplete="off">
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" type="button" value="Delete"
                                    onclick="deleteRowDebtOpen(this)" autocomplete="off"><i
                                        class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <button type="button" id="add_more" class="btn btn-primary"
                                    onclick="addAccountOpen('debitVoucher');"
                                    autocomplete="off">@localize('add_more')</button>
                            </td>
                            <td colspan="2" class="text-end">
                                <label for="reason" class="  col-form-label">@localize('total')</label>
                            </td>

                            <td class="text-end">
                                <input type="text" id="grandTotal" class="form-control text-end" name="grand_total"
                                    value="" readonly="readonly" autocomplete="off">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </x-card>
</x-app-layout>
@push('js')
    <script>
        $(document).ready(function() {
            $(".select2").select2();
        });
    </script>
@endpush
