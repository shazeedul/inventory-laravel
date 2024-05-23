<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\FinancialYear;
use Illuminate\Contracts\Support\Renderable;
use Modules\Account\Entities\AccountSubCode;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\DataTables\ContraVoucherDataTable;

class ContraVoucherController extends Controller
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
            'title' => 'Contra Voucher List',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Contra Voucher List',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.voucher.contra',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ContraVoucherDataTable $dataTable)
    {
        return $dataTable->render('account::vouchers.contra.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Contra Voucher',
            'description' => 'Creating Contra Voucher.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Contra Voucher Lists',
                    'link' => route('admin.account.voucher.contra.index'),
                ],
                [
                    'name' => 'Create Contra Voucher',
                    'link' => false,
                ],
            ],
        ]);

        $accounts = ChartOfAccount::where('head_level', 4)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('is_bank_nature', true)
                    ->orWhere('is_cash_nature', true);
            })
            ->orderBy('name', 'ASC')
            ->get();

        return view('account::vouchers.contra.create', compact('accounts'));
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

        foreach ($request->contras as $value) {
            AccountVoucher::create([
                'chart_of_account_id' => $value['coa_id'],
                'reverse_code' => $request->account_head,
                'financial_year_id' => $financial_year->id,
                'voucher_date' => $request->voucher_date,
                'account_voucher_type_id' => 3,
                'cheque_no' => $request->cheque_no,
                'cheque_date' => $request->cheque_date,
                'is_honour' => isset($request->is_honour) ? $request->is_honour : 0,
                'narration' => $request->remarks,
                'account_sub_type_id' => $value['sub_type_id'] ?? null,
                'account_sub_code_id' => $value['sub_code_id'] ?? null,
                'ledger_comment' => $value['ledger_comment'] ?? '',
                'debit' => $value['debit'] ?? 0.00,
                'credit' => $value['credit'] ?? 0.00,
                'voucher_no' =>  $voucher_no,
            ]);
        }

        return redirect()->route('admin.account.voucher.contra.index')->with('success', localize('Contra Voucher created successfully.'));
    }

    /**
     * Show the specified resource.
     * @param AccountVoucher $contra
     * @return Renderable
     */
    public function show(AccountVoucher $contra)
    {
        $contra->load(['reverseCode', 'chartOfAccount', 'accountSubCode']);
        return view('account::vouchers.contra.show', compact('contra'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param AccountVoucher $contra
     * @return Renderable
     */
    public function edit(AccountVoucher $contra)
    {
        cs_set('theme', [
            'title' => 'Contra Voucher',
            'description' => 'Edit Contra Voucher.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Contra Voucher Lists',
                    'link' => route('admin.account.voucher.contra.index'),
                ],
                [
                    'name' => 'Edit Contra Voucher',
                    'link' => false,
                ],
            ],
        ]);

        $accounts = ChartOfAccount::where('head_level', 4)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('is_bank_nature', true)
                    ->orWhere('is_cash_nature', true);
            })
            ->orderBy('name', 'ASC')
            ->get();

        $accountSubCodes = AccountSubCode::where('status', true)->get();

        return view(
            'account::vouchers.contra.edit',
            compact('accounts', 'contra', 'accountSubCodes')
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param AccountVoucher $contra
     * @return Renderable
     */
    public function update(Request $request, AccountVoucher $contra)
    {
        $request->validate([
            'account_head' => 'required',
            'voucher_date' => 'required',
        ]);

        $financial_year = FinancialYear::where('status', true)->where('is_closed', false)->first();
        // Extract IDs from the contras array
        $contraIds = collect($request->contras)->pluck('id')->toArray();

        // Delete vouchers where voucher_no is in contra voucher numbers and id is not in contra ids
        AccountVoucher::where('voucher_no', $contra->voucher_no)
            ->whereNotIn('id', $contraIds)
            ->delete();

        foreach ($request->contras as $value) {
            AccountVoucher::updateOrCreate([
                'id' => $value['id'],
            ], [
                'chart_of_account_id' => $value['coa_id'],
                'reverse_code' => $request->account_head,
                'financial_year_id' => $financial_year->id,
                'voucher_date' => $request->voucher_date,
                'account_voucher_type_id' => 3,
                'cheque_no' => $request->cheque_no,
                'cheque_date' => $request->cheque_date,
                'is_honour' => $request->is_honour ?? 0,
                'narration' => $request->remarks,
                'account_sub_type_id' => $value['sub_type_id'] ?? null,
                'account_sub_code_id' => $value['sub_code_id'] ?? null,
                'ledger_comment' => $value['ledger_comment'] ?? '',
                'debit'          => $value['debit'] ?? 0.00,
                'credit'          => $value['credit'] ?? 0.00,
                'voucher_no' => $contra->voucher_no,
            ]);
        }

        return redirect()->route('admin.account.voucher.contra.index')->with('success', localize('Contra Voucher updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param AccountVoucher $contra
     */
    public function destroy(AccountVoucher $contra)
    {
        $contra->delete();
        return response()->success('', localize('Contra Voucher deleted successfully.'));
    }
}
