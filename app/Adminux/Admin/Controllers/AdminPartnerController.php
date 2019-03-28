<?php

namespace App\Adminux\Admin\Controllers;

use App\Adminux\Admin\Models\Admin;
use App\Adminux\Partner\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class AdminPartnerController extends Controller
{

    public function getDatatable($obj)
    {
        if(new \ReflectionClass(new Admin) == new \ReflectionClass($obj)) {
            $model = $obj->partners();
            $title = __('adminux.name');
            $column = 'name';
        } else {
            $model = $obj->admins();
            $title = 'Admin';
            $column = 'email';
        }

        if(request()->ajax()) return Datatables::of($model)
            ->addColumn('actions', 'adminux.components.datatables.link_delete_button')
            ->rawColumns(['actions'])
            ->toJson();

        return [
            'model' => $model,
            'route' => 'dddd',
            'thead' => '<th style="min-width:30px">ID</th>
                        <th class="w-75">'.$title.'</th>
                        <th style="min-width:120px">Created At</th>
                        <th>Action</th>',
            'columns' => "{ data: 'id', name: 'admin_partner.id', className: 'text-center' },
                          { data: '".$column."', name: '".$column."' },
                          { data: 'created_at', name: 'admin_partner.created_at', className: 'text-center' },
                          { data: 'actions', name: 'actions', className: 'text-center', orderable: false }"

        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Admin $admin)
    {
        $request->validate([
            'name'   => 'required|unique:'.$partner->getTable(),
            'active' => 'in:Y,""',
        ]);

        // $admin->partners()->attach(($request->all());

        //         $shop = Shop::find($shop_id);
        // $shop->products()->attach($product_id);

        if(!$request->filled('active')) $request->merge(['active' => 'N']);

        $partner = $partner->create($request->all());

        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin, Partner $partner)
    {
        dd(request()->partner);
        $admin->partners()->detach($partner->id);

        return back();
    }
}
