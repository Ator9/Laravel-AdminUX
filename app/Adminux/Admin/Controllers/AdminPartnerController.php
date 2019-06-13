<?php

namespace App\Adminux\Admin\Controllers;

use App\Adminux\Admin\Models\Admin as BaseModel;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class AdminPartnerController extends Controller
{
    public function getIndex($obj)
    {
        if(new \ReflectionClass(new BaseModel) == new \ReflectionClass($obj)) {
            $model = $obj->partners();
            $title = __('adminux.name');
            $column = 'partner';
        } else {
            $model = $obj->admins();
            $title = 'Admin';
            $column = 'email';
        }

        if(request()->ajax()) {
            if(request()->filled('search.value')) {
                $dt = Datatables::of($model->getRelated()::query())->addColumn('actions', function($row) use ($obj) {
                    $params['action'] = url(request()->route()->getPrefix().'/adminpartner');
                    $params['table'] = $obj->getTable();
                    $params['id'] = $obj->id;
                    $params['related_id'] = $row->id;
                    return view('adminux.components.datatables.link_add_button', compact('params'));
                });
            } else {
                $dt = Datatables::of($model)->addColumn('actions', function($row) use ($column) {
                    $params['action'] = url(request()->route()->getPrefix().'/adminpartner/'.$row->id);
                    $params['title']  = 'Delete '.$row->{$column}.'?';
                    return view('adminux.components.datatables.link_delete_button', compact('params'));
                });
            }

            return $dt->rawColumns(['actions'])->toJson();
        }

        return [
            'model' => $model,
            'thead' => '<th class="w-100">'.$title.'</th>
                        <th style="min-width:100px">Action</th>',
            'columns' => '{ data: "'.$column.'", name: "'.$column.'" },
                          { data: "actions", name: "actions", className: "text-center", orderable: false }'
        ];
    }

    public function setPartner()
    {
        if(in_array(request()->partner_id, $this->getEnabledPartnersKeys())) session(['partner_id' => request()->partner_id]);
        else session(['partner_id' => '']);

        return back();
    }

    public function getEnabledPartners()
    {
        return auth('adminux')->user()->partners();
    }

    public function getEnabledPartnersKeys()
    {
        return $this->getEnabledPartners()->get()->keyBy('id')->keys()->toArray();
    }

    public function getSelectedPartners()
    {
        return in_array(session('partner_id'), $this->getEnabledPartnersKeys()) ? [ session('partner_id') ] : $this->getEnabledPartnersKeys();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BaseModel $model)
    {
        if(request()->get('table') == $model->getTable()) {
            $relation = $model->find(request()->get('id'))->partners();
        } else {
            $relation = $model->partners()->getRelated()->find(request()->get('id'))->admins();
        }

        $relation->syncWithoutDetaching([request()->get('related_id')]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::table((new BaseModel)->partners()->getTable())->where('id', '=', $id)->delete();

        return back();
    }
}
