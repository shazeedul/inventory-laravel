<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\FinancialYear;
use Illuminate\Contracts\Support\Renderable;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\DataTables\DebitVoucherDataTable;
use Modules\Account\Entities\AccountSubCode;

class DebitVoucherController extends Controller
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
            'title' => 'Debit Voucher List',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Debit Voucher List',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.voucher.debit',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DebitVoucherDataTable $dataTable)
    {
        return $dataTable->render('account::vouchers.debit.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Debit Voucher',
            'description' => 'Creating Debit Voucher.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Debit Voucher Lists',
                    'link' => route('admin.account.voucher.debit.index'),
                ],
                [
                    'name' => 'Create Debit Voucher',
                    'link' => false,
                ],
            ],
        ]);

        $accounts = ChartOfAccount::where('head_level', 4)->where('is_active', true)->orderBy('name', 'ASC')->get();

        return view('account::vouchers.debit.create', compact('accounts'));
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

        foreach ($request->debits as $value) {
            AccountVoucher::create([
                'chart_of_account_id' => $value['coa_id'],
                'reverse_code' => $request->account_head,
                'financial_year_id' => $financial_year->id,
                'voucher_date' => $request->voucher_date,
                'account_voucher_type_id' => 1,
                'cheque_no' => $request->cheque_no,
                'cheque_date' => $request->cheque_date,
                'is_honour' => isset($request->is_honour) ? $request->is_honour : 0,
                'narration' => $request->remarks,
                'account_sub_type_id' => $value['sub_type_id'] ?? null,
                'account_sub_code_id' => $value['sub_code_id'] ?? null,
                'ledger_comment' => $value['ledger_comment'] ?? '',
                'debit' => $value['amount'] ?? 0.00,
                'voucher_no' =>  $voucher_no,
            ]);
        }

        return redirect()->route('admin.account.voucher.debit.index')->with('success', localize('Debit Voucher created successfully.'));
    }

    /**
     * Show the specified resource.
     * @param AccountVoucher $debit
     * @return Renderable
     */
    public function show(AccountVoucher $debit)
    {
        $debit->load(['reverseCode', 'chartOfAccount', 'accountSubCode']);
        return view('account::vouchers.debit.show', compact('debit'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param AccountVoucher $debit
     * @return Renderable
     */
    public function edit(AccountVoucher $debit)
    {
        cs_set('theme', [
            'title' => 'Debit Voucher',
            'description' => 'Edit Debit Voucher.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Debit Voucher Lists',
                    'link' => route('admin.account.voucher.debit.index'),
                ],
                [
                    'name' => 'Edit Debit Voucher',
                    'link' => false,
                ],
            ],
        ]);

        $accounts = ChartOfAccount::where('head_level', 4)->where('is_active', true)->orderBy('name', 'ASC')->get();

        $accountSubCodes = AccountSubCode::where('status', true)->get();

        return view('account::vouchers.debit.edit', compact('accounts', 'debit', 'accountSubCodes'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param AccountVoucher $debit
     * @return Renderable
     */
    public function update(Request $request, AccountVoucher $debit)
    {
        $request->validate([
            'account_head' => 'required',
            'voucher_date'       => 'required',
        ]);

        $financial_year = FinancialYear::where('status', true)->where('is_closed', false)->first();
        // Extract IDs from the debits array
        $debitIds = collect($request->debits)->pluck('id')->toArray();

        // Delete vouchers where voucher_no is in debit voucher numbers and id is not in debit ids
        AccountVoucher::where('voucher_no', $debit->voucher_no)
            ->whereNotIn('id', $debitIds)
            ->delete();


        foreach ($request->debits as $value) {
            AccountVoucher::updateOrCreate([
                'id' => $value['id'],
            ], [
                'chart_of_account_id' => $value['coa_id'],
                'reverse_code' => $request->account_head,
                'financial_year_id' => $financial_year->id,
                'voucher_date' => $request->voucher_date,
                'account_voucher_type_id' => 1,
                'cheque_no' => $request->cheque_no,
                'cheque_date' => $request->cheque_date,
                'is_honour' => $request->is_honour ?? 0,
                'narration' => $request->remarks,
                'account_sub_type_id' => $value['sub_type_id'] ?? null,
                'account_sub_code_id' => $value['sub_code_id'] ?? null,
                'ledger_comment' => $value['ledger_comment'] ?? '',
                'debit'          => $value['amount'] ?? 0.00,
                'voucher_no' => $debit->voucher_no,
            ]);
        }

        return redirect()->route('admin.account.voucher.debit.index')->with('success', localize('Debit Voucher updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param AccountVoucher $debit
     */
    public function destroy(AccountVoucher $debit)
    {
        $debit->delete();
        return response()->success('', localize('Debit Voucher deleted successfully.'));
    }
}
