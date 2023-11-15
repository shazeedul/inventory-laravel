<?php

namespace Modules\Invoice\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Customer;
use Modules\Invoice\DataTables\InvoiceDataTable;
use Modules\Product\Entities\Product;

class InvoiceController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy', 'approve']]);
        // set the strip scripts tag middleware for the controller
        // $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        $this->middleware(['auth', 'verified', 'permission:invoice_management']);
        \cs_set('theme', [
            'title' => 'Invoice Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Invoice Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.invoice',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(InvoiceDataTable $dataTable)
    {
        return $dataTable->render('invoice::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Invoice',
            'description' => 'Creating New Invoice.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Invoice Lists',
                    'link' => route('admin.invoice.index'),
                ],
                [
                    'name' => 'Create New Invoice',
                    'link' => false,
                ],
            ],
        ]);

        $products = Product::with(['category:id,name', 'unit:id,name'])->whereStatus(1)->get();
        $customers = Customer::whereStatus(1)->get();

        return view('invoice::create', compact('products', 'customers'));
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
        return view('invoice::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('invoice::edit');
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
