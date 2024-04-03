<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\DataTables\CategoryDataTable;
use Modules\Product\Entities\Category;

class CategoryController extends Controller
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
        $this->middleware(['auth', 'verified', 'permission:category_management']);
        \cs_set('theme', [
            'title' => 'Category Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Category Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.category',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CategoryDataTable $dataTable)
    {
        \cs_set('theme', [
            'description' => 'Display a listing of categories.',
        ]);

        return $dataTable->render('product::category.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Category',
            'description' => 'Creating New Category.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Category Lists',
                    'link' => route('admin.category.index'),
                ],
                [
                    'name' => 'Create New Category',
                    'link' => false,
                ],
            ],
        ]);

        return view('product::category.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create($data);

        return response()->success('', 'Category created successfully.');
    }

    /**
     * Show the specified resource.
     * @param Category $category
     * @return Renderable
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param Category $category
     * @return Renderable
     */
    public function edit(Category $category)
    {
        \cs_set('theme', [
            'update' => route(config('theme.rprefix') . '.update', $category->id),
        ]);

        return view('product::category.create_edit', ['item' => $category]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Category $category
     * @return Renderable
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        $category->update($data);

        return response()->success('', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Category $category
     * @return Renderable
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->success('', 'Category deleted successfully.', 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Category $category, Request $request)
    {
        $category->update(['status' => $request->status]);

        return \response()->success($category, 'Category Status Updated Successfully.', 200);
    }
}
