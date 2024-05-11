<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
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
            ->where('is_subtype', false)
            ->where('is_active', true)
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
        //
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
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
