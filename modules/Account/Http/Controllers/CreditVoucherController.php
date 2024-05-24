<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\FinancialYear;
use Illuminate\Contracts\Support\Renderable;
use Modules\Account\Entities\AccountSubCode;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\DataTables\CreditVoucherDataTable;

class CreditVoucherController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy']]);
        // set the strip scripts tag middleware for the controller
        // $this->middleware(['permission:account_predefine_update'])->only(['store']);
        $this->middleware(['auth', 'verified']);
        \cs_set('theme', [
            'title' => 'Credit Voucher List',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Credit Voucher List',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.voucher.credit',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CreditVoucherDataTable $dataTable)
    {
        return $dataTable->render('account::vouchers.credit.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Credit Voucher',
            'description' => 'Creating Credit Voucher.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Credit Voucher Lists',
                    'link' => route('admin.account.voucher.credit.index'),
                ],
                [
                    'name' => 'Create Credit Voucher',
                    'link' => false,
                ],
            ],
        ]);

        $accounts = ChartOfAccount::where('head_level', 4)->where('is_active', true)->orderBy('name', 'ASC')->get();

        return view('account::vouchers.credit.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_head' => 'required',
            'voucher_date' => 'required',
        ]);

        $financial_year = FinancialYear::where('status', true)->where('is_closed', false)->first();
        $latestVoucher  = AccountVoucher::orderBy('created_at', 'DESC')->first();
        $voucher_no = str_pad(($latestVoucher ? $latestVoucher->id : 0) + 1, 6, "0", STR_PAD_LEFT);

        foreach ($request->credits as $value) {
            AccountVoucher::create([
                'chart_of_account_id' => $value['coa_id'],
                'reverse_code' => $request->account_head,
                'financial_year_id' => $financial_year->id,
                'voucher_date' => $request->voucher_date,
                'account_voucher_type_id' => 2,
                'cheque_no' => $request->cheque_no,
                'cheque_date' => $request->cheque_date,
                'is_honour' => isset($request->is_honour) ? $request->is_honour : 0,
                'narration' => $request->remarks,
                'account_sub_type_id' => $value['sub_type_id'] ?? null,
                'account_sub_code_id' => $value['sub_code_id'] ?? null,
                'ledger_comment' => $value['ledger_comment'] ?? '',
                'credit' => $value['amount'] ?? 0.00,
                'voucher_no' =>  $voucher_no,
            ]);
        }

        return redirect()->route('admin.account.voucher.credit.index')->with('success', localize('Credit Voucher created successfully.'));
    }

    /**
     * Show the specified resource.
     * @param AccountVoucher $credit
     * @return Renderable
     */
    public function show(AccountVoucher $credit)
    {
        $credit->load(['reverseCode', 'chartOfAccount', 'accountSubCode']);
        return view('account::vouchers.credit.show', compact('credit'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param AccountVoucher $credit
     * @return Renderable
     */
    public function edit(AccountVoucher $credit)
    {
        cs_set('theme', [
            'title' => 'Credit Voucher',
            'description' => 'Edit Credit Voucher.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Credit Voucher Lists',
                    'link' => route('admin.account.voucher.credit.index'),
                ],
                [
                    'name' => 'Edit Credit Voucher',
                    'link' => false,
                ],
            ],
        ]);

        $accounts = ChartOfAccount::where('head_level', 4)->where('is_active', true)->orderBy('name', 'ASC')->get();

        $accountSubCodes = AccountSubCode::where('status', true)->get();

        return view('account::vouchers.credit.edit', compact('accounts', 'credit', 'accountSubCodes'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param AccountVoucher $credit
     * @return Renderable
     */
    public function update(Request $request, AccountVoucher $credit)
    {
        $request->validate([
            'account_head' => 'required',
            'voucher_date' => 'required',
        ]);

        $financial_year = FinancialYear::where('status', true)->where('is_closed', false)->first();
        // Extract IDs from the debits array
        $creditIds = collect($request->debits)->pluck('id')->toArray();

        // Delete vouchers where voucher_no is in credit voucher numbers and id is not in credit ids
        AccountVoucher::where('voucher_no', $credit->voucher_no)
            ->whereNotIn('id', $creditIds)
            ->delete();

        foreach ($request->credits as $value) {
            AccountVoucher::updateOrCreate([
                'id' => $value['id'],
            ], [
                'chart_of_account_id' => $value['coa_id'],
                'reverse_code' => $request->account_head,
                'financial_year_id' => $financial_year->id,
                'voucher_date' => $request->voucher_date,
                'account_voucher_type_id' => 2,
                'cheque_no' => $request->cheque_no,
                'cheque_date' => $request->cheque_date,
                'is_honour' => $request->is_honour ?? 0,
                'narration' => $request->remarks,
                'account_sub_type_id' => $value['sub_type_id'] ?? null,
                'account_sub_code_id' => $value['sub_code_id'] ?? null,
                'ledger_comment' => $value['ledger_comment'] ?? '',
                'credit'          => $value['amount'] ?? 0.00,
                'voucher_no' => $credit->voucher_no,
            ]);
        }

        return redirect()->route('admin.account.voucher.credit.index')->with('success', localize('Credit Voucher updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param AccountVoucher $credit
     */
    public function destroy(AccountVoucher $credit)
    {
        $credit->delete();
        return response()->success('', localize('Credit Voucher deleted successfully.'));
    }
}
