<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\AccountSubType;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\Currency;

class ChartOfAccountController extends Controller
{
    /**
     * construct function
     */
    public function __construct()
    {
        // set the strip scripts tag middleware for the controller
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        $this->middleware(['auth', 'verified', 'permission:coa_management']);
        \cs_set('theme', [
            'title' => 'Chart Of Account',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Chart Of Account',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.financial-year',
        ]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $accMainHead = ChartOfAccount::where('is_active', 1)->where('head_level', 1)->where('parent_id', 0)->get();
        $accSecondLabelHead = ChartOfAccount::where('is_active', 1)->where('head_level', 2)->get();
        $accHeadWithoutFands = ChartOfAccount::where('is_active', 1)
            ->whereNot('head_level', 2)
            ->whereNot('head_level', 1)->get();

        $subTypes = AccountSubType::where('status', 1)->get();
        $currencies = Currency::where('status', 1)->get();

        return view('account::coa.index', compact('accMainHead', 'accSecondLabelHead', 'accHeadWithoutFands', 'subTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('account::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required',
            'head_level' => 'required',
            'parent_id' => 'required',
            'account_type_id' => 'required',
            'is_active' => 'required',
        ]);
        if ($request->asset_type == 'is_stock') {
            $validated['is_stock'] = 1;
        }
        if ($request->asset_type == 'is_fixed_asset') {
            $validated['is_fixed_asset_schedule'] = 1;
            $validated['asset_code'] = $request->asset_code;
            $validated['depreciation_rate'] = $request->depreciation_rate;
        }

        if ($request->asset_type == 'is_subtype') {
            $validated['is_subtype'] = 1;
            $validated['account_sub_type_id'] = $request->account_sub_type_id;
        }
        if ($request->asset_type == 'is_cash') {
            $validated['is_cash_nature'] = 1;
        }
        if ($request->asset_type == 'is_bank') {
            $validated['is_bank_nature'] = 1;
        }
        // for liability & equity
        if (($request->head_level == 4) && (($request->account_type_id == 2) || ($request->account_type_id == 5))) {
            $validated['depreciation_code'] = $request->depreciation_code;
        }
        if ((($request->head_level == 3) || ($request->head_level == 4))) {
            $validated['note_no'] = $request->note_no;
        }

        ChartOfAccount::create($validated);

        return redirect()->route('admin.account.coa.index')->with('success', 'Chart Of Account Created Successfully');
    }

    /**
     * Show the specified resource.
     * @param ChartOfAccount $chartOfAccount
     */
    public function show(ChartOfAccount $chartOfAccount)
    {
        return response()->success($chartOfAccount);
    }

    /**
     * Show the form for editing the specified resource.
     * @param ChartOfAccount $chartOfAccount
     */
    public function edit(ChartOfAccount $chartOfAccount)
    {
        $dropDownAccounts = ChartOfAccount::parentAccounts($chartOfAccount->id, $chartOfAccount->account_type_id);
        return response()->success([
            'chartOfAccount' => $chartOfAccount,
            'dropDownAccounts' => $dropDownAccounts,
        ], localize('Chart Of Account Edit Data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        // dd($request->all());
        // validation rules
        $validated = $request->validate([
            'name' => 'required',
            'parent_id' => 'required',
            'is_active' => 'sometimes|required_if:parent_id,!=,0|nullable',
        ]);
        if ($request->parent_id != 0) {
            $GetParentCoa = ChartOfAccount::findOrFail($request->parent_id);
            $head_level = (int) $GetParentCoa->head_level + 1;
            $account_type_id = $GetParentCoa->account_type_id;
            $validated['account_type_id'] = $account_type_id;
            $validated['head_level'] = $head_level;
        } else {
            $account_type_id = $request->account_type_id;
            $head_level = $request->currentHeadLabel;
            $validated['account_type_id'] = $account_type_id;
            $validated['head_level'] = $head_level;
        }

        if (($account_type_id == 1) && ($head_level == 3)) {

            if ($request->asset_type == 'is_stock') {
                $validated['is_stock'] = 1;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }
            if ($request->asset_type == 'is_fixed_asset') {
                $validated['is_stock'] = 0;
                $validated['is_fixed_asset_schedule'] = 1;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }
        }

        if ((($account_type_id == 2) || ($account_type_id == 5)) && ($head_level == 3)) {

            if ($request->asset_type == 'is_fixed_asset') {
                $validated['is_fixed_asset_schedule'] = 1;
            } else {
                $validated['is_fixed_asset_schedule'] = 0;
            }
            $validated['asset_code'] = null;
            $validated['depreciation_rate'] = null;
            $validated['depreciation_code'] = null;
        }

        if (($account_type_id == 1) && ($head_level == 4)) {

            if ($request->asset_type == 'is_cash') {

                $validated['is_cash_nature'] = 1;
                $validated['is_bank_nature'] = 0;
                $validated['is_stock'] = 0;
                $validated['is_subtype'] = 0;
                $validated['account_sub_type_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }
            if ($request->asset_type == 'is_bank') {

                $validated['is_bank_nature'] = 1;
                $validated['is_cash_nature'] = 0;
                $validated['is_stock'] = 0;
                $validated['is_subtype'] = 0;
                $validated['account_sub_type_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }

            if ($request->asset_type == 'is_stock') {

                $validated['is_stock'] = 1;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
                $validated['is_subtype'] = 0;
                $validated['account_sub_type_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }

            if ($request->asset_type == 'is_fixed_asset') {

                $validated['is_fixed_asset_schedule'] = 1;
                $validated['asset_code'] = $request->asset_code;
                $validated['depreciation_rate'] = $request->depreciation_rate;
                $validated['is_stock'] = 0;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
                $validated['is_subtype'] = 0;
                $validated['account_sub_type_id'] = null;
            }

            if ($request->asset_type == 'is_subtype') {

                $validated['is_subtype'] = 1;
                $validated['account_sub_type_id'] = $request->account_sub_type_id;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
                $validated['is_stock'] = 0;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
            }
        }

        if ((($account_type_id == 2) || ($account_type_id == 3)) && ($head_level == 4)) {

            if ($request->asset_type == 'is_subtype') {
                $validated['is_subtype'] = 1;
                $validated['account_sub_type_id'] = $request->account_sub_type_id;
            } else {
                $validated['is_subtype'] = 0;
                $validated['account_sub_type_id'] = null;
            }
            $validated['asset_code'] = null;
            $validated['depreciation_rate'] = null;
            $validated['depreciation_code'] = null;

            $validated['is_fixed_asset_schedule'] = 0;
            $validated['depreciation_rate'] = null;
            $validated['is_stock'] = 0;
            $validated['is_bank_nature'] = 0;
            $validated['is_cash_nature'] = 0;
            $validated['note_no'] = $request->note_no;
        }

        if ((($account_type_id == 2) || ($account_type_id == 4) || ($account_type_id == 5)) && ($head_level == 4)) {

            if ($request->asset_type == 'is_fixed_asset') {
                $validated['is_fixed_asset_schedule'] = 1;
                $validated['depreciation_code'] = $request->depreciation_code;

                $validated['is_subtype'] = 0;
                $validated['account_sub_type_id'] = null;
            }
            if ($request->asset_type == 'is_subtype') {
                $validated['is_subtype'] = 1;
                $validated['account_sub_type_id'] = $request->account_sub_type_id;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['depreciation_code'] = null;
            }

            $validated['asset_code'] = null;
            $validated['depreciation_rate'] = null;
            $validated['is_stock'] = 0;
            $validated['is_bank_nature'] = 0;
            $validated['is_cash_nature'] = 0;
            $validated['note_no'] = $request->note_no;
        }

        if ((($head_level == 3) || ($head_level == 4))) {
            $validated['note_no'] = $request->note_no;
        }

        DB::beginTransaction();
        try {
            $chartOfAccount = ChartOfAccount::findOrFail($request->id);
            $chartOfAccount->update($validated);
            $this->updateTreeLabel($chartOfAccount); // Call the helper function to update child accounts recursively
            DB::commit();
            return redirect()->route('admin.account.coa.index')->with('success', localize('Chart Of Account Updated Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', localize('Something went wrong'));
        }
        return redirect()->route('admin.account.coa.index')->with('success', localize('Chart Of Account Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $chartOfAccount = ChartOfAccount::findOrFail($request->id);
        $chartOfAccount->delete();
        return redirect()->route('admin.account.coa.index')->with('success', localize('Chart Of Account Deleted Successfully'));
    }

    public function updateTreeLabel($latestCoaUpdate)
    {
        $account_type_id = $latestCoaUpdate->account_type_id;

        // Helper function to update child accounts recursively
        function updateChildren($parentId, $level, $account_type_id)
        {
            $children = ChartOfAccount::where('parent_id', $parentId)->get();
            if ($children->isNotEmpty()) {
                foreach ($children as $child) {
                    $child->account_type_id = $account_type_id;
                    $child->head_level = $level + 1;
                    $child->save(); // Use save() for efficient updates

                    updateChildren($child->id, $child->head_level, $account_type_id); // Recursive call
                }
            }
        }

        // Start the update process
        updateChildren($latestCoaUpdate->id, $latestCoaUpdate->head_level, $account_type_id);

        return true; // Return true assuming success (adjust based on your logic)
    }
}
