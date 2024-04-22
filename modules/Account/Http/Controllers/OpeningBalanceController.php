<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\DataTables\OpeningBalanceDataTable;
use Modules\Account\Entities\AccountOpeningBalance;
use Modules\Account\Entities\AccountSubCode;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\FinancialYear;

class OpeningBalanceController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy']]);
        // set the strip scripts tag middleware for the controller
        $this->middleware(['permission:opening_balance_management'])->only(['index']);
        $this->middleware(['permission:opening_balance_create'])->only(['store']);
        $this->middleware(['permission:opening_balance_update'])->only(['update']);
        $this->middleware(['permission:opening_balance_delete'])->only(['destroy']);
        $this->middleware(['auth', 'verified']);
        \cs_set('theme', [
            'title' => 'Opening Balance Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Opening Balance Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.opening.balance',
        ]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(OpeningBalanceDataTable $dataTable)
    {
        return $dataTable->render('account::opening_balance.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        \cs_set('theme', [
            'title' => 'Opening Balance Create',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Opening Balance',
                    'link' => route('admin.account.opening.balance.index'),
                ],
                [
                    'name' => 'Opening Balance Create',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.opening.balance',
        ]);
        $financial_years = FinancialYear::where('status', false)->get();
        $accounts = ChartOfAccount::where('head_level', 4)->whereIn('account_type_id', [1, 2, 5])->where('is_active', true)->get(['id', 'name', 'code', 'account_sub_type_id']);
        return view('account::opening_balance.create_edit', compact('financial_years', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'financial_year_id' => 'required|integer',
            'opening_date' => 'required|date',
            'chart_of_account_id' => 'required|array',
            'chart_of_account_id.*' => 'required|integer',
            'account_sub_type_id' => 'nullable|array',
            'account_sub_type_id.*' => 'nullable|integer',
            'account_sub_code_id' => 'nullable|array',
            'account_sub_code_id.*' => 'nullable|integer',
            'debit' => 'nullable|array',
            'debit.*' => 'nullable|numeric',
            'credit' => 'nullable|array',
            'credit.*' => 'nullable|numeric',
        ]);

        foreach ($request->chart_of_account_id as $key => $value) {
            AccountOpeningBalance::create([
                'financial_year_id' => $request->financial_year_id,
                'opening_date' => $request->opening_date,
                'chart_of_account_id' => $value,
                'account_sub_type_id' => $request->account_sub_type_id[$key] ?? null,
                'account_sub_code_id' => $request->account_sub_code_id[$key] ?? null,
                'debit' => $request->debit[$key] == 0 ? null : $request->debit[$key],
                'credit' => $request->credit[$key] == 0 ? null : $request->credit[$key],
            ]);
        }

        return redirect()->route('admin.account.opening.balance.index')->with('success', localize('Opening Balance created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param AccountOpeningBalance $openingBalance
     * @return Renderable
     */
    public function edit(Request $request, AccountOpeningBalance $openingBalance)
    {
        \cs_set('theme', [
            'title' => 'Opening Balance Edit',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Opening Balance',
                    'link' => route('admin.account.opening.balance.index'),
                ],
                [
                    'name' => 'Opening Balance Edit',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.opening.balance',
        ]);

        $financial_years = FinancialYear::where('status', false)->get();
        $accounts = ChartOfAccount::where('head_level', 4)->whereIn('account_type_id', [1, 2, 5])->where('is_active', true)->get(['id', 'name', 'code', 'account_sub_type_id']);
        $subCodes = AccountSubCode::where('account_sub_type_id', $openingBalance->account_sub_type_id)->where('status', true)->get(['id', 'name']);
        $data = $openingBalance;
        return view('account::opening_balance.create_edit', compact('financial_years', 'accounts', 'data', 'subCodes'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param AccountOpeningBalance $openingBalance
     * @return Renderable
     */
    public function update(Request $request, AccountOpeningBalance $openingBalance)
    {
        $request->validate([
            'financial_year_id' => 'required|integer',
            'opening_date' => 'required|date',
            'chart_of_account_id' => 'required|array',
            'chart_of_account_id.*' => 'required|integer',
            'account_sub_type_id' => 'nullable|array',
            'account_sub_type_id.*' => 'nullable|integer',
            'account_sub_code_id' => 'nullable|array',
            'account_sub_code_id.*' => 'nullable|integer',
            'edit_id' => 'nullable|array',
            'edit_id.*' => 'nullable|integer',
            'debit' => 'nullable|array',
            'debit.*' => 'nullable|numeric',
            'credit' => 'nullable|array',
            'credit.*' => 'nullable|numeric',
        ]);

        foreach ($request->chart_of_account_id as $key => $value) {
            AccountOpeningBalance::updateOrCreate([
                'id' => $request->edit_id[$key],
            ], [
                'financial_year_id' => $request->financial_year_id,
                'opening_date' => $request->opening_date,
                'chart_of_account_id' => $value,
                'account_sub_type_id' => $request->account_sub_type_id[$key] ?? null,
                'account_sub_code_id' => $request->account_sub_code_id[$key] ?? null,
                'debit' => $request->debit[$key] == 0 ? null : $request->debit[$key],
                'credit' => $request->credit[$key] == 0 ? null : $request->credit[$key],
            ]);
        }

        return redirect()->route('admin.account.opening.balance.index')->with('success', localize('Opening Balance updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param AccountOpeningBalance $openingBalance
     * @return Renderable
     */
    public function destroy(AccountOpeningBalance $openingBalance)
    {
        $openingBalance->delete();

        return response()->success('', localize('Opening Balance deleted successfully.'));
    }
}
