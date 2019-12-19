<?php

namespace App\Adminux\Admin\Controllers;

use Spatie\Permission\Models\Role;
// use App\Adminux\Admin\Controllers\AdminRoleController;
use Illuminate\Http\Request;
use App\Adminux\AdminuxController;
use Yajra\Datatables\Datatables;

class RoleController extends AdminuxController
{
    public function __construct()
    {
        $this->middleware('adminux_superuser');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        if(request()->ajax()) return Datatables::of($role::query())
            ->addColumn('id2', 'adminux.pages.inc.link_show_link')
            ->rawColumns(['id2'])
            ->toJson();

        return view('adminux.pages.index')->withDatatables([
            'order' => '[[ 0, "asc" ]]',
            'thead' => '<th style="min-width:30px">ID</th>
                        <th class="w-75">Name</th>
                        <th class="w-75">Guard Name</th>
                        <th style="min-width:120px">Created At</th>',

            'columns' => '{ data: "id2", name: "id", className: "text-center" },
                          { data: "name", name: "name" },
                          { data: "guard_name", name: "guard_name" },
                          { data: "created_at", name: "created_at", className: "text-center" }'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Role $role)
    {
        return parent::createView($role);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Role $role)
    {
        $request->validate([
            'name'   => 'required|unique:'.$role->getTable(),
        ]);

        if(!$request->filled('guard_name')) $request->merge(['guard_name' => '']);

        $role = $role->create($request->all());

        return parent::saveRedirect($role);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        // if(request()->ajax()) return (new AdminRoleController)->getIndex($role);

        return view('adminux.pages.show')->withModel($role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return parent::editView($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'   => 'required|unique:'.$role->getTable().',name,'.$role->id,
        ]);

        if(!$request->filled('guard_name')) $request->merge(['guard_name' => '']);

        $role->update($request->all());

        return parent::saveRedirect($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return parent::destroyRedirect();
    }

    /**
     * Build Blade edit & create form fields
     *
     * @return Array
     */
    public function getFields(Role $role)
    {
        $form = new \App\Adminux\Form($role);
        return [
            $form->display([ 'label' => 'ID' ]),
            $form->text([ 'label' => 'Name' ]),
            $form->text([ 'label' => 'Guard Name', 'value' => 'adminux' ]), // TODO default value on insert
        ];
    }
}
