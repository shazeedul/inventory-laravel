<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Entities\Unit;
use Illuminate\Routing\Controller;
use Modules\Product\DataTables\UnitDataTable;
use Illuminate\Contracts\Support\Renderable;

class UnitController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy', 'statusUpdate']]);
        // set the strip scripts tag middleware for the controller
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        $this->middleware(['auth', 'verified', 'permission:unit_management']);
        \cs_set('theme', [
            'title' => 'Unit Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Unit Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.unit',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(UnitDataTable $dataTable)
    {
        \cs_set('theme', [
            'description' => 'Display a listing of units.',
        ]);

        return $dataTable->render('product::unit.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Unit',
            'description' => 'Creating New Unit.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Unit Lists',
                    'link' => route('admin.unit.index'),
                ],
                [
                    'name' => 'Create New Unit',
                    'link' => false,
                ],
            ],
        ]);

        return view('product::unit.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
        ]);

        Unit::create($data);

        return response()->success('', 'Unit created successfully.');
    }

    /**
     * Show the specified resource.
     * @param Unit $unit
     * @return Renderable
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param Unit $unit
     * @return Renderable
     */
    public function edit(Unit $unit)
    {
        \cs_set('theme', [
            'update' => route(config('theme.rprefix') . '.update', $unit->id),
        ]);

        return view('product::unit.create_edit', ['item' => $unit]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Unit $unit
     * @return Renderable
     */
    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name,' . $unit->id],
        ]);

        $unit->update($data);

        return response()->success('', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Unit $unit
     * @return Renderable
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return response()->success('', 'Unit deleted successfully.', 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Unit $unit, Request $request)
    {
        $unit->update(['status' => $request->status]);

        return \response()->success($unit, 'Unit Status Updated Successfully.', 200);
    }
}
