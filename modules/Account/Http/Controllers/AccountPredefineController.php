<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\AccountPredefine;
use Modules\Account\Entities\ChartOfAccount;

class AccountPredefineController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy']]);
        // set the strip scripts tag middleware for the controller
        $this->middleware(['permission:account_predefine_update'])->only(['store']);
        $this->middleware(['auth', 'verified', 'permission:account_predefine']);
        \cs_set('theme', [
            'title' => 'Account Predefine Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Account Predefine Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.predefine',
        ]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $predefines = config('account.default_predefine');
        $raw_predefines = AccountPredefine::all();
        $levels = [];
        foreach ($predefines as $p) {
            if ($p['level'] && !isset($levels[$p['level']])) {
                $levels[$p['level']] = ChartOfAccount::where('head_level', $p['level'])->where('is_active', true)->select(['id', 'name'])->get();
            }
        }
        return view('account::predefine.index', compact('predefines', 'raw_predefines', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'predefines' => 'required|array',
        ]);

        foreach ($data['predefines'] as $key => $coa_id) {
            AccountPredefine::updateOrCreate([
                'key' => $key,

            ], [
                'chart_of_account_id' => $coa_id,
            ]);
        }

        return back()->with('success', localize('Account Predefine updated successfully'));
    }
}
