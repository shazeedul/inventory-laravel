<?php

namespace Modules\Supplier\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Supplier\DataTables\SupplierDataTable;
use Modules\Supplier\Entities\Supplier;

class SupplierController extends Controller
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
        $this->middleware(['auth', 'verified', 'permission:supplier_management']);
        \cs_set('theme', [
            'title' => 'Supplier Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Supplier Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.supplier',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(SupplierDataTable $dataTable)
    {
        \cs_set('theme', [
            'description' => 'Display a listing of suppliers.',
        ]);

        return $dataTable->render('supplier::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Supplier',
            'description' => 'Creating New Supplier.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Supplier Lists',
                    'link' => route('admin.supplier.index'),
                ],
                [
                    'name' => 'Create New Supplier',
                    'link' => false,
                ],
            ],
        ]);

        return view('supplier::create_edit');
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
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'mobile_no' => 'nullable|string|max:255|unique:suppliers,mobile_no',
            'address' => 'nullable|string|max:255',
        ]);

        Supplier::create($data);

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Show the specified resource.
     * @param Supplier $supplier
     * @return Renderable
     */
    public function show(Supplier $supplier)
    {
        return view('supplier::show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Supplier $supplier
     * @return Renderable
     */
    public function edit(Supplier $supplier)
    {
        \cs_set('theme', [
            'update' => route(config('theme.rprefix') . '.update', $supplier->id),
        ]);

        return view('supplier::create_edit', ['item' => $supplier]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Supplier $supplier
     * @return Renderable
     */
    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'mobile_no' => 'nullable|string|max:255|unique:suppliers,mobile_no,' . $supplier->id,
            'address' => 'nullable|string|max:255',
        ]);

        $supplier->update($data);

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Supplier $supplier
     * @return Renderable
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->success('', 'Supplier deleted successfully.',200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Supplier $supplier, Request $request)
    {
        $supplier->update(['status' => $request->status]);

        return \response()->success($supplier, 'Supplier Status Updated Successfully.', 200);
    }
}
