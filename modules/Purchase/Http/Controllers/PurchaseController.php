<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Unit\Entities\Unit;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;
use Modules\Supplier\Entities\Supplier;
use Illuminate\Contracts\Support\Renderable;
use Modules\Purchase\DataTables\PurchaseDataTable;

class PurchaseController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        // $this->middleware('request:ajax', ['only' => ['destroy', 'statusUpdate']]);
        // set the strip scripts tag middleware for the controller
        // $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        $this->middleware(['auth', 'verified', 'permission:purchase_management']);
        \cs_set('theme', [
            'title' => 'Purchase Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Purchase Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.purchase',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PurchaseDataTable $dataTable)
    {
        return $dataTable->render('purchase::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Purchase',
            'description' => 'Creating New Purchase.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Purchase Lists',
                    'link' => route('admin.purchase.index'),
                ],
                [
                    'name' => 'Create New Purchase',
                    'link' => false,
                ],
            ],
        ]);

        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::with(['category:id,name', 'unit:id,name'])
            ->where('status', 1)
            ->get(['id', 'name', 'quantity', 'category_id', 'unit_id']);


        return view('purchase::create', compact('suppliers', 'products'));
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
        return view('purchase::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('purchase::edit');
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