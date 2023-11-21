<?php

namespace Modules\Invoice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Invoice\Entities\Invoice;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Session;
use Modules\Customer\Entities\Customer;
use Illuminate\Contracts\Support\Renderable;
use Modules\Invoice\DataTables\InvoiceDataTable;
use Modules\Invoice\Http\Requests\InvoiceStoreRequest;

class InvoiceController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['destroy']]);
        // set the strip scripts tag middleware for the controller
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
     * @param InvoiceStoreRequest $request
     * @return Renderable
     */
    public function store(InvoiceStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'date' => $request->date,
            ]);

            $invoice->invoiceDetails()->createMany(
                array_map(function ($product_id, $quantity, $unit_price, $total) {
                    return [
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                        'unit_price' => $unit_price,
                        'price' => $total,
                    ];
                }, $request->product_id, $request->quantity, $request->unit_price, $request->total)
            );

            DB::commit();

            Session::flash('success', 'Invoice Create Successfully.');

            return redirect()->route('admin.invoice.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param Invoice $invoice
     * @return Renderable
     */
    public function show(Invoice $invoice)
    {
        $invoice->with(['invoiceDetails' => function ($q) {
            $q->with('product:id,name');
        }, 'customer'])->first();
        return view('invoice::show', ['invoice' => $invoice]);
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
     * @param Invoice  $invoice
     * @return Renderable
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->invoiceDetails()->delete();
        $invoice->delete();

        return response()->success('', 'Invoice deleted successfully.');
    }

    /**
     * Approve the specified resource from storage.
     * @param $invoice
     * @return Renderable
     */
    public function approveList($invoice)
    {
        cs_set('theme', [
            'title' => 'Invoice approve list',
            'description' => 'Invoice approve list.',
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
                    'name' => 'Invoice approve list',
                    'link' => false,
                ],
            ],
        ]);

        $invoice = Invoice::with(['invoiceDetails' => function ($q) {
            $q->with(['product:id,name,quantity']);
        }, 'customer'])->findOrFail($invoice);

        return view('invoice::approve', ['invoice' => $invoice]);
    }

    public function approve($invoice)
    {
        $invoice = Invoice::findOrFail($invoice);
        // check invoice already approve
        if ($invoice->status == 1) {
            return response()->error('', 'Invoice already approved.');
        }

        $invoice->with(['invoiceDetails' => function ($q) {
            $q->with(['product:id,name,quantity']);
        }]);

        // check invoice details data each product quantity not getter then item quantity
        foreach ($invoice->invoiceDetails as $invoiceDetail) {
            if ($invoiceDetail->product->quantity < $invoiceDetail->quantity) {
                return response()->error('', 'Product quantity not getter then item quantity.');
            }
        }

        // update invoice status
        $invoice->update(['status' => 1]);
        // decrease product quantity after approved
        foreach ($invoice->invoiceDetails as $invoiceDetail) {
            $invoiceDetail->product->decrement('quantity', $invoiceDetail->quantity);
        }

        return response()->success(['redirect_url' => route('admin.invoice.index'), 'Invoice approve successfully.']);
    }
}
