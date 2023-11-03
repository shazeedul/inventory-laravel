<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Customer;
use Illuminate\Contracts\Support\Renderable;
use Modules\Customer\DataTables\CustomerDataTable;

class CustomerController extends Controller
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
        $this->middleware(['auth', 'verified', 'permission:customer_management']);
        \cs_set('theme', [
            'title' => 'Customer Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Customer Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.customer',
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CustomerDataTable $dataTable)
    {
        return $dataTable->render('customer::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Customer',
            'description' => 'Creating New Customer.',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Customer Lists',
                    'link' => route('admin.customer.index'),
                ],
                [
                    'name' => 'Create New Customer',
                    'link' => false,
                ],
            ],
        ]);

        return view('customer::create_edit');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        dd(1);
        return response()->success('', 'Customer created successfully.');
    }

    /**
     * Show the specified resource.
     * @param Customer $customer
     * @return Renderable
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param Customer $customer
     * @return Renderable
     */
    public function edit(Customer $customer)
    {
        \cs_set('theme', [
            'update' => route(config('theme.rprefix') . '.update', $customer->id),
        ]);

        return view('customer::create_edit', ['item' => $customer]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Customer $customer
     * @return Renderable
     */
    public function update(Request $request, Customer $customer)
    {
        dd(1);
        return response()->success('', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Customer $customer
     * @return Renderable
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->success('', 'Customer deleted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Customer $customer, Request $request)
    {
        $customer->update(['status' => $request->status]);

        return \response()->success($customer, 'Customer Status Updated Successfully.', 200);
    }
}
