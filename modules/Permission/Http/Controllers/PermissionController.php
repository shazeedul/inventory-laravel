<?php

namespace Modules\Permission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Permission\DataTables\PermissionDataTable;
use Modules\Permission\Entities\Permission;

class PermissionController extends Controller
{
    /**
     * Constructor for the controller.
     *
     * @return void
     */
    public function __construct()
    {
        // set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        // set the strip scripts tag middleware for the controller
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        // set permission middleware for the controller
        $this->middleware(['auth', 'verified', 'permission:permission_management']);
        \cs_set('theme', [
            'title' => 'Permission Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Permission Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.permission',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PermissionDataTable $dataTable)
    {
        \cs_set('theme', [
            'description' => 'Display a listing of roles in Database.',
        ]);

        return $dataTable->render('permission::index');
    }

    /**
     * Create the specified resource.
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Fact',
        ]);
        $groups = Permission::groupList();

        return view('permission::create_edit', compact('groups'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'group' => 'nullable|string|max:255',
        ]);

        $permission = Permission::create([
            'name' => implode('_', \explode(' ', Str::lower($data['name']))),
            'group' => $data['group'] ?? null,
        ]);

        return response()->success($permission, 'Permission created successfully.', 201);
    }

    /**
     * Show the show page for showing the specified resource.
     */
    public function edit(Permission $permission)
    {
        cs_set('theme', [
            'title' => 'Edit Existing Permission',
            'update' => route(config('theme.rprefix').'.update', $permission->id),
        ]);

        // return the response
        return view('permission::create_edit')->with('item', $permission)->with('groups', Permission::groupList())->render();
    }

    /**
     * Update the specified resource in storage.
     *
     *  @return \Illuminate\Http\Response
     */
    public function update(Permission $permission, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$permission->id.',id',
            'group' => 'nullable|string|max:255',

        ]);
        $permission->update([
            'name' => implode('_', \explode(' ', Str::lower($data['name']))),
            'group' => $data['group'] ?? null,
        ]);
        $permission->syncPermissions($request->permissions ?? '');

        return response()->success($permission, 'Permission updated successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     *  @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->success(null, 'Permission deleted successfully.', 200);
    }
}
