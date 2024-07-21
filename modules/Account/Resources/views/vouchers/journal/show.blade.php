<!-- Modal -->
<div class="" style="padding: 50px 0">
    <div class="row gutters-sm m-3">
        <div class="col-md-12" id="print-table-{{ $journal->id }}">
            <div class="row">
                <div class="col-12 col-6">
                    <div class="fs-10 text-start pb-3">
                        @localize('print_date'): {{ \Carbon\Carbon::now()->format('d-m-Y h:i:sa') }} ,
                        @localize('user'):
                        {{ auth()->user()->name }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-left-3">
                    <img src="" alt="Logo" height="40px"><br><br>
                </div>
                <div class="col-middle-6 text-center">
                    <h6>{{ config('app.name') }}</h6>

                    <strong><u class="pt-4">@localize('debit')</u></strong>
                </div>
                <div class="col-right-3"></div>
                <div class="col-full-12 text-end">
                    <label class="font-weight-600 mb-0">@localize('voucher_no')</label> :
                    {{ $journal->voucher_no }}<br>
                    <label class="font-weight-600 mb-0">@localize('voucher_date')</label> :
                    {{ $journal->voucher_date }}
                </div>
            </div>

            <table class="table table-bordered table-sm mt-2 voucher">
                <thead>
                    <tr>
                        <th class="text-center">@localize('particulars')</th>
                        <th class="text-end">@localize('debit')</th>
                        <th class="text-end">@localize('credit')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($journal->all_vouchers_by_no as $voucher)
                        <tr>
                            <td>
                                <strong>{{ $voucher->chartOfAccount->name ?? ' ' }}
                                    @if ($voucher->accountSubCode)
                                        - ( {{ $voucher->accountSubCode?->name }} )
                                    @endif
                                </strong>
                                @if ($voucher->ledger_comment)
                                    <br>
                                    <span> {{ $voucher->ledger_comment }}</span>
                                @endif
                                @if ($voucher->is_bank_nature = true)
                                    @if ($voucher->cheque_no)
                                        <span>@localize('check_no'): {{ $voucher->cheque_no }},
                                        </span>
                                    @endif
                                    @if ($voucher->cheque_date)
                                        <span>@localize('check_date'):
                                            {{ $voucher->cheque_date }}</span>
                                    @endif
                                @endif
                            </td>

                            <td class="text-end"> {{ $voucher->debit }}</td>
                            <td class="text-end">0</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                            <strong>{{ $journal->reverse_code ? $journal->reverseCode?->name : ' ' }}</strong>
                            <br>
                            @if ($journal->reverseCode?->is_bank_nature)
                                @if ($journal->cheque_no)
                                    <span>@localize('check_no'): {{ $journal->cheque_no }}, </span>
                                @endif
                                @if ($journal->cheque_date)
                                    <span>@localize('check_date'): {{ $journal->cheque_date }}</span>
                                @endif
                            @endif
                        </td>

                        <td class="text-end">0</td>
                        <td class="text-end">
                            {{ $journal->all_vouchers_by_no->sum('debit') }}
                        </td>
                    </tr>

                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end">@localize('total')</th>
                        <th class="text-end">
                            {{ $journal->all_vouchers_by_no->sum('debit') }}
                        </th>
                        <th class="text-end">
                            {{ $journal->all_vouchers_by_no->sum('debit') }}
                        </th>
                    </tr>
                    <tr>
                        <th class="" colspan="3">@localize('in_words'):
                            {{-- {{ numberToWords($journal->all_vouchers_by_no->sum('debit')) }} --}}
                        </th>
                    </tr>
                    <tr>
                        <th class="" colspan="3">@localize('remarks'):
                            {{ $journal->narration ?? ' ' }}</th>
                    </tr>
                </tfoot>
            </table>
            <div class="form-group row mt-5">
                <label for="received_by" class="col-3 text-center">
                    <div class="border-top">@localize('received_by')</div>
                </label>
                <label for="prepared_by" class="col-3 text-center">
                    <div class="border-top">@localize('prepared_by')</div>
                </label>
                <label for="executive_accounts" class="col-3 text-center">
                    <div class="border-top">@localize('executive_accounts')</div>
                </label>
                <label for="approved_by" class="col-3 text-center">
                    <div class="border-top">@localize('approved_by')</div>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer d-print-none">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('close')</button>
    {{-- <button type="button" onclick="printVoucher('print-table-{{ $journal->id }}')"
        class="btn btn-primary">@localize('print')</button> --}}
</div>
