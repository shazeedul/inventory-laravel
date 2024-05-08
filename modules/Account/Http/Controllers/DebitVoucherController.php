<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\FinancialYear;
use Illuminate\Contracts\Support\Renderable;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\DataTables\DebitVoucherDataTable;

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
            'voucher_date'       => 'required',
        ]);

        $financial_year = FinancialYear::where('status', true)->where('is_closed', false)->first();
        $latestVoucher  = AccountVoucher::orderBy('created_at', 'DESC')->first();

        foreach ($request->debits as $key => $value) {
            $voucher = new AccountVoucher();
            $voucher->chart_of_account_id = $value['coa_id'];
            $voucher->reverse_code = $request->account_head;
            $voucher->financial_year_id = $financial_year->id;
            $voucher->voucher_date = $request->voucher_date;
            $voucher->account_voucher_type_id = 1;
            $voucher->narration = $request->remarks;
            $voucher->account_sub_type_id = $value['sub_type_id'] ?? null;
            $voucher->account_sub_code_id = $value['sub_code_id'] ?? null;
            $voucher->voucher_no = 'DV-' . str_pad(($latestVoucher ? $latestVoucher->id : 0) + 1, 6, "0", STR_PAD_LEFT);
            $voucher->ledger_comment = $value['ledger_comment'] ?? '';
            $voucher->debit          = $value['amount'] ?? 0.00;
            $voucher->save();
        }

        return redirect()->route('admin.account.voucher.debit.index')->with('success', localize('Debit Voucher created successfully.'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('account::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('account::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param AccountVoucher $voucher
     */
    public function destroy(AccountVoucher $voucher)
    {
        dd($voucher->voucher_no);
        $voucher->delete();
        return response()->success('', localize('Debit Voucher deleted successfully.'));
    }
}
