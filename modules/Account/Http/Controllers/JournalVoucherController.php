<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\FinancialYear;
use Illuminate\Contracts\Support\Renderable;
use Modules\Account\Entities\AccountSubCode;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\DataTables\JournalVoucherDataTable;

class JournalVoucherController extends Controller
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
        // $this->middleware(['auth', 'verified', 'permission:predefine_account']);
        \cs_set('theme', [
            'title' => 'Journal Voucher List',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Journal Voucher List',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.voucher.journal',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(JournalVoucherDataTable $dataTable)
    {
        return $dataTable->render('account::vouchers.journal.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Journal Voucher',
            'description' => 'Creating Journal Voucher.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Journal Voucher Lists',
                    'link' => route('admin.account.voucher.journal.index'),
                ],
                [
                    'name' => 'Create Journal Voucher',
                    'link' => false,
                ],
            ],
        ]);

        $accounts = ChartOfAccount::where('head_level', 4)
            ->where('is_active', true)
            ->orderBy('name', 'ASC')
            ->get();

        return view('account::vouchers.journal.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_date' => 'required',
            'journals' => 'required|array',
            'journals.*.coa_id' => 'required',
            'journals.*.debit' => 'required_without:journals.*.credit',
            'journals.*.credit' => 'required_without:journals.*.debit',
        ], [
            'voucher_date.required' => 'The voucher date field is required.',
            'journals.required' => 'The journal field is required.',
            'journals.*.coa_id.required' => 'The account head field is required.',
            'journals.*.debit.required_without' => 'The debit field is required when credit field is empty.',
            'journals.*.credit.required_without' => 'The credit field is required when debit field is empty.',
        ]);

        $financial_year = FinancialYear::where('status', true)->where('is_closed', false)->first();
        $latestVoucher  = AccountVoucher::orderBy('created_at', 'DESC')->first();
        $voucher_no = str_pad(($latestVoucher ? $latestVoucher->id : 0) + 1, 6, "0", STR_PAD_LEFT);

        $debitEntries = [];
        $creditEntries = [];

        // Separate debit and credit entries
        foreach ($request->journals as $key => $value) {
            if ($value['debit'] > 0) {
                $debitEntries[] = ['key' => $key, 'entry' => $value];
            } elseif ($value['credit'] > 0) {
                $creditEntries[] = ['key' => $key, 'entry' => $value];
            }
        }

        // dd($debitEntries);


        foreach ($debitEntries as $debit) {
            $debitIndex = $debit['key'];
            $nearestCredit = $this->findNearestCredit($debitIndex, $creditEntries);

            if ($nearestCredit !== null) {
                $nearestCreditCoaId = $nearestCredit['entry']['coa_id'];
                $test[] = $nearestCreditCoaId;
                $nearestCreditKey[] = $nearestCredit['key'];
                // AccountVoucher::create([
                //     'chart_of_account_id' => $debit['entry']['coa_id'],
                //     'reverse_code' => $nearestCreditCoaId, // Use the nearest credit's coa_id
                //     'financial_year_id' => $financial_year->id,
                //     'voucher_date' => $request->voucher_date,
                //     'account_voucher_type_id' => 4,
                //     'cheque_no' => $request->cheque_no,
                //     'cheque_date' => $request->cheque_date,
                //     'is_honour' => isset($request->is_honour) ? $request->is_honour : 0,
                //     'narration' => $request->remarks,
                //     'account_sub_type_id' => $debit['entry']['sub_type_id'] ?? null,
                //     'account_sub_code_id' => $debit['entry']['sub_code_id'] ?? null,
                //     'ledger_comment' => $debit['entry']['ledger_comment'] ?? '',
                //     'debit' => $debit['entry']['debit'] ?? 0.00,
                //     'credit' => $debit['entry']['credit'] ?? 0.00,
                //     'voucher_no' => $voucher_no,
                // ]);
            }
        }
        dd($test, $nearestCreditKey);

        return redirect()->route('admin.account.voucher.journal.index')->with('success', localize('Journal Voucher created successfully.'));
    }

    /**
     * Show the specified resource.
     * @param AccountVoucher $journal
     * @return Renderable
     */
    public function show(AccountVoucher $journal)
    {
        $journal->load(['reverseCode', 'chartOfAccount', 'accountSubCode']);
        return view('account::vouchers.journal.show', compact('journal'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param AccountVoucher $journal
     * @return Renderable
     */
    public function edit(AccountVoucher $journal)
    {
        cs_set('theme', [
            'title' => 'Journal Voucher',
            'description' => 'Edit Journal Voucher.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Journal Voucher Lists',
                    'link' => route('admin.account.voucher.journal.index'),
                ],
                [
                    'name' => 'Edit Journal Voucher',
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
            'account::vouchers.journal.edit',
            compact('accounts', 'journal', 'accountSubCodes')
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param AccountVoucher $journal
     * @return Renderable
     */
    public function update(Request $request, AccountVoucher $journal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param AccountVoucher $journal
     * @return Renderable
     */
    public function destroy(AccountVoucher $journal)
    {
        //
    }

    // Function to find the nearest credit entry
    function findNearestCredit($debitIndex, $creditEntries)
    {
        $nearestCredit = null;
        $minDistance = PHP_INT_MAX;

        foreach ($creditEntries as $credit) {
            $distance = abs($debitIndex - $credit['key']);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearestCredit = $credit;
            }
        }

        return $nearestCredit;
    }
}
