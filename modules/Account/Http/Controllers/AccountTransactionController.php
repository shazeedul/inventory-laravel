<?php

namespace Modules\Account\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Illuminate\Contracts\Support\Responsable;
use Modules\Account\DataTables\PendingVoucherDataTable;
use Modules\Account\Entities\AccountTransaction;

class AccountTransactionController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy']]);
        // set the strip scripts tag middleware for the controller
        $this->middleware(['permission:transaction_management'])->only(['index']);
        $this->middleware(['auth', 'verified']);
        \cs_set('theme', [
            'title' => 'Voucher Transaction',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Voucher Transaction',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.transaction',
        ]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PendingVoucherDataTable $dataTable)
    {
        \cs_set('theme', [
            'title' => 'Pending Voucher List',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Pending Voucher List',
                    'link' => false,
                ],
            ],
        ]);

        return $dataTable->render('account::transaction.approval');
    }

    /**
     * Approve the bulk voucher transaction.
     * @param Request $request
     * @return Responsable
     */
    public function approve(Request $request)
    {
        if (empty($request->voucherId)) {
            return response()->error([], 'No Voucher is Selected for Approval', 422);
        }

        $vouchers = AccountVoucher::whereIn('id', $request->voucherId)->get();
        $reverseInsert     = [];
        $normalInsert      = [];
        $current_date_time = Carbon::now()->toDateTimeString();
        foreach ($vouchers as $key => $value) {
            $normalInsert[$key]['chart_of_account_id'] = $value->chart_of_account_id;
            $normalInsert[$key]['financial_year_id']  = $value->financial_year_id;
            $normalInsert[$key]['account_sub_type_id'] = $value->account_sub_type_id;
            $normalInsert[$key]['account_sub_code_id'] = $value->account_sub_code_id;
            $normalInsert[$key]['account_voucher_type_id'] = $value->account_voucher_type_id;
            $normalInsert[$key]['voucher_no'] = $value->voucher_no;
            $normalInsert[$key]['voucher_date'] = date('Y-m-d', strtotime($value->voucher_date));
            $normalInsert[$key]['reference_type'] = $value->reference_type;
            $normalInsert[$key]['reference_id'] = $value->reference_id;
            $normalInsert[$key]['narration'] = $value->narration;
            $normalInsert[$key]['cheque_no'] = $value->cheque_no;
            $normalInsert[$key]['cheque_date'] = $value->cheque_date;
            $normalInsert[$key]['is_honour'] = $value->is_honour;
            $normalInsert[$key]['ledger_comment'] = $value->ledger_comment;
            $normalInsert[$key]['debit'] = $value->debit;
            $normalInsert[$key]['credit'] = $value->credit;
            $normalInsert[$key]['reverse_code'] = $value->reverse_code;

            $subType = ChartOfAccount::where('id', $value->reverse_code)->whereNotNull('account_sub_type_id')->first();

            $reverseInsert[$key]['chart_of_account_id'] = $value->reverse_code;
            $reverseInsert[$key]['financial_year_id']  = $value->financial_year_id;
            $reverseInsert[$key]['account_sub_type_id'] = $subType->account_sub_type_id ?? null;
            $reverseInsert[$key]['account_sub_code_id'] = $value->account_sub_code_id;
            $reverseInsert[$key]['account_voucher_type_id'] = $value->account_voucher_type_id;
            $reverseInsert[$key]['voucher_no'] = $value->voucher_no;
            $reverseInsert[$key]['voucher_date'] = date('Y-m-d', strtotime($value->voucher_date));
            $reverseInsert[$key]['reference_type'] = $value->reference_type;
            $reverseInsert[$key]['reference_id'] = $value->reference_id;
            $reverseInsert[$key]['narration'] = $value->narration;
            $reverseInsert[$key]['cheque_no'] = $value->cheque_no;
            $reverseInsert[$key]['cheque_date'] = $value->cheque_date;
            $reverseInsert[$key]['is_honour'] = $value->is_honour;
            $reverseInsert[$key]['ledger_comment'] = $value->ledger_comment;
            $reverseInsert[$key]['debit'] = $value->credit;
            $reverseInsert[$key]['credit'] = $value->debit;
            $reverseInsert[$key]['reverse_code'] = $value->chart_of_account_id;
        }

        DB::transaction(function () use ($normalInsert, $reverseInsert, $request, $current_date_time) {
            AccountVoucher::whereIn('id', $request->voucherId)->update([
                'is_approved' => 1,
                'approved_by' => auth()->user()->id,
                'approved_at' => $current_date_time,
            ]);

            AccountTransaction::insert($normalInsert);
            AccountTransaction::insert($reverseInsert);
        });

        return response()->success([], 'Voucher transaction approved successfully.', 200);
    }

    /**
     * Restore Transaction voucher
     * @param AccountVoucher $voucher
     * @return Responsable
     */
    public function restore(AccountVoucher $voucher)
    {
    }
}
