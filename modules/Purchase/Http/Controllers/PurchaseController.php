<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Session;
use Modules\Category\Entities\Category;
use Modules\Purchase\Entities\Purchase;
use Modules\Supplier\Entities\Supplier;
use Illuminate\Contracts\Support\Renderable;
use Modules\Purchase\DataTables\PurchaseDataTable;
use Modules\Purchase\Http\Requests\PurchaseStoreRequest;

class PurchaseController extends Controller
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
     * @param PurchaseStoreRequest $request
     * @return Renderable
     */
    public function store(PurchaseStoreRequest $request)
    {
        try {
            $request->validated();
            DB::beginTransaction();

            // Create purchase
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'total_price' => $request->total_price,
            ]);

            $purchase->purchaseDetails()->createMany(
                array_map(function ($product_id, $quantity, $unit_price, $description, $total) {
                    return [
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                        'unit_price' => $unit_price,
                        'description' => $description,
                        'price' => $total,
                    ];
                }, $request->product_id, $request->quantity, $request->unit_price, $request->description, $request->total)
            );

            DB::commit();

            Session::flush('success', 'Purchase Create Successfully.');

            return redirect()->route('admin.purchase.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withInput()->withErrors('Some thing wrong.' . $th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param Purchase $purchase
     * @return Renderable
     */
    public function show(Purchase $purchase)
    {
        $purchase->with(['purchaseDetails' => function ($q) {
            $q->with('product:id,name');
        }, 'supplier'])->first();

        return view('purchase::show', ['purchase' => $purchase]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Purchase $purchase
     * @return Renderable
     */
    public function edit(Purchase $purchase)
    {
        \cs_set('theme', [
            'update' => route(config('theme.rprefix') . '.update', $purchase->id),
        ]);

        cs_set('theme', [
            'title' => 'Edit Purchase',
            'description' => 'Editing Purchase.',
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
                    'name' => 'Edit Purchase',
                    'link' => false,
                ],
            ],
        ]);

        // dd($purchase->with('purchaseDetails')->first());

        return view('purchase::edit', [
            'purchase' => $purchase->with('purchaseDetails')->first(),
            'suppliers' => Supplier::where('status', 1)->get(),
            'products' => Product::with(['category:id,name', 'unit:id,name'])
                ->where('status', 1)
                ->get(['id', 'name', 'quantity', 'category_id', 'unit_id']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param PurchaseStoreRequest $request
     * @param Purchase $purchase
     * @return Renderable
     */
    public function update(PurchaseStoreRequest $request, Purchase $purchase)
    {
        $request->validated();
        DB::beginTransaction();
        try {
            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'total_price' => $request->total_price,
            ]);

            // delete purchase details
            foreach ($purchase->purchaseDetails as $purchaseDetail) {
                if (!in_array($purchaseDetail->id, $request->purchase_details_id)) {
                    $purchaseDetail->delete();
                }
            }

            // details update or create
            foreach ($request->product_id as $key => $product_id) {

                $purchase->purchaseDetails()->updateOrCreate(
                    [
                        'id' => $request->purchase_details_id[$key],
                    ],
                    [
                        'product_id' => $product_id,
                        'quantity' => $request->quantity[$key],
                        'unit_price' => $request->unit_price[$key],
                        'description' => $request->description[$key],
                        'price' => $request->total[$key],
                    ]
                );
            }

            DB::commit();
            Session::flush('success', 'Purchase Update Successfully.');
            return redirect()->route('admin.purchase.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors('Some thing wrong.' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Purchase $purchase
     * @return Renderable
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->purchaseDetails()->delete();
        $purchase->delete();

        return response()->success('', 'Purchase deleted successfully.');
    }

    /**
     * Purchase status update
     * @param Request $request
     * @param Purchase $purchase
     * @return void
     */
    public function approve(Request $request, Purchase $purchase)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $purchase->update([
            'status' => $request->status,
        ]);

        // update details table product iterate quantity increase
        foreach ($purchase->purchaseDetails as $purchaseDetail) {
            $product = Product::find($purchaseDetail->product_id);
            $product->update([
                'quantity' => $product->quantity + $purchaseDetail->quantity,
            ]);
        }

        return response()->success('', 'Purchase status update successfully.');
    }
}
