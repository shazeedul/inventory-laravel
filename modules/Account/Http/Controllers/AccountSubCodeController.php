<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\DataTables\SubCodeDataTable;
use Modules\Account\Entities\AccountSubCode;

class AccountSubCodeController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy']]);
        // set the strip scripts tag middleware for the controller
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        $this->middleware(['auth', 'verified', 'permission:sub_code_management']);
        \cs_set('theme', [
            'title' => 'Sub Code Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Sub Code Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.sub_code',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(SubCodeDataTable $dataTable)
    {
        return $dataTable->render('account::sub_code.index');
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

    /**
     * Get SubCode information by ChatOfAccount ID
     * @param Request $request
     * @return JsonResponse
     */
    public function getSubCodeBySubType(Request $request)
    {
        $subCodes = AccountSubCode::where('account_sub_type_id', $request->subType)->where('status', true)->get(['id', 'name']);
        return \response()->success($subCodes, localize('Sub Code List'), 200);
    }
}
