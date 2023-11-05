<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Unit\Entities\Unit;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;
use Modules\Supplier\Entities\Supplier;
use Illuminate\Contracts\Support\Renderable;
use Modules\Product\DataTables\ProductDataTable;

class ProductController extends Controller
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
        $this->middleware(['auth', 'verified', 'permission:product_management']);
        \cs_set('theme', [
            'title' => 'Product Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Product Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.product',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProductDataTable $dataTable)
    {
        \cs_set('theme', [
            'description' => 'Display a listing of products.',
        ]);

        return $dataTable->render('product::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Product',
            'description' => 'Creating New Product.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Product Lists',
                    'link' => route('admin.product.index'),
                ],
                [
                    'name' => 'Create New Product',
                    'link' => false,
                ],
            ],
        ]);

        $units = Unit::where('status', true)->get();
        $categories = Category::where('status', true)->get();

        return view('product::create_edit', compact('units', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id' => 'required|integer',
            'category_id' => 'required|integer',
            'name' => 'required|string|max:191',
        ]);

        Product::create($data);

        return response()->success('', 'Product created successfully.');
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
     * @param Product $product
     * @return Renderable
     */
    public function edit(Product $product)
    {
        \cs_set('theme', [
            'update' => route(config('theme.rprefix') . '.update', $product->id),
        ]);

        $units = Unit::where('status', true)->get();
        $categories = Category::where('status', true)->get();

        return view('product::create_edit', ['item' => $product, 'units' => $units, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Product $product
     * @return Renderable
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'unit_id' => 'required|integer',
            'category_id' => 'required|integer',
            'name' => 'required|string|max:191',
        ]);

        $product->update($data);

        return response()->success('', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Product $product
     * @return Renderable
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->success('', 'Product deleted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Product $product, Request $request)
    {
        $product->update(['status' => $request->status]);

        return \response()->success($product, 'Product Status Updated Successfully.', 200);
    }
}
