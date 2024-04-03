<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\DataTables\FinancialYearDataTable;
use Modules\Account\Entities\FinancialYear;

class FinancialYearController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy']]);
        // set the strip scripts tag middleware for the controller
        $this->middleware('strip_scripts_tag')->only(['store', 'update', 'closeStore']);
        $this->middleware(['auth', 'verified', 'permission:financial_year_management']);
        \cs_set('theme', [
            'title' => 'Financial Year Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Financial Year Lists',
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
    public function index(FinancialYearDataTable $dataTable)
    {
        return $dataTable->render('account::financial-year.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Financial Year',
            'description' => 'Creating New Financial Year.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Financial Year Lists',
                    'link' => route('admin.account.financial-year.index'),
                ],
                [
                    'name' => 'Create New Financial Year',
                    'link' => false,
                ],
            ],
        ]);

        return view('account::financial-year.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|boolean',
        ]);

        FinancialYear::create($data);
        return response()->success('', 'Financial Year created successfully.', 201);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param FinancialYear $financialYear
     * @return Renderable
     */
    public function edit(FinancialYear $financialYear)
    {
        \cs_set('theme', [
            'update' => route(config('theme.rprefix') . '.update', $financialYear->id),
        ]);

        return view('account::financial-year.create_edit', ['item' => $financialYear]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param FinancialYear $financialYear
     * @return Renderable
     */
    public function update(Request $request, FinancialYear $financialYear)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|boolean',
        ]);

        $financialYear->update($data);
        return response()->success('', 'Financial Year updated successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param FinancialYear $financialYear
     * @return Renderable
     */
    public function destroy(FinancialYear $financialYear)
    {
        $financialYear->delete();
        return response()->success('', 'Financial Year deleted successfully.', 200);
    }

    /**
     * Close Financial Year
     * @return Renderable
     */
    public function close()
    {
        \cs_set('theme', [
            'title' => 'Close Financial Year',
            'description' => 'Close Financial Year.',
            'rprefix' => 'admin.account.financial-year',
        ]);

        return view('account::financial-year.closing_year', ['financialYears' => FinancialYear::where('status', false)->get()]);
    }

    /**
     * close the specified financial year
     * @param Request $request
     */
    public function closeStore(Request $request)
    {
        $financialYear = FinancialYear::findOrFail($request->year);
        $financialYear->status = false;
        $financialYear->is_closed = true;
        $financialYear->save();
        return response()->success('', 'Financial Year closed successfully.', 200);
    }
}
