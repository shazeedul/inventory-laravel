<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy']]);
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
        $accMainHead         = ChartOfAccount::where('is_active', 1)->where('head_level', 1)->where('parent_id', 0)->get();
        $accSecondLabelHead  = ChartOfAccount::where('is_active', 1)->where('head_level', 2)->get();
        $accHeadWithoutFands = ChartOfAccount::where('is_active', 1)
            ->whereNot('head_level', 2)
            ->whereNot('head_level', 1)->get();

        $subTypes = AccountSubType::where('status', 1)->get();
        $currencies = Currency::where('status', 1)->get();

        return view('account::coa.index', compact('accMainHead', 'accSecondLabelHead',  'accHeadWithoutFands', 'subTypes'));
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
        //
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
        return response()->success('');
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
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
