<?php

namespace App\Adminux\Admin\Controllers;

use App\Adminux\Admin\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Adminux\Form;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Admin $admin)
    {
        if(isset($_GET['datatables'])) return Datatables::of($admin::query())
            ->addColumn('active', 'adminux.components.datatables.status')
            ->rawColumns(['active'])
            ->toJson();

        return view('adminux.components.datatables.index')->withDatatables([
            'thead' => '<th class="text-center">ID</th>
                        <th class="w-75">E-mail</th>
                        <th class="text-center">Active</th>
                        <th class="text-center">Created At</th>',

            'columns' => "{ data: 'id', name: 'id', className: 'text-center' },
                          { data: 'email', name: 'email' },
                          { data: 'active', name: 'active', className: 'text-center' },
                          { data: 'created_at', name: 'created_at', className: 'text-center' }"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return view('adminux.components.show')->withModel($admin);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $fields = [
            Form::input(),
            Form::textarea(),
        ];

        return view('adminux.components.edit')->withModel($admin)->withFields($fields);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

    /**
     * Display admin dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('adminux.dashboard');
    }

    /**
     * Display admin logs
     *
     * @return \Illuminate\Http\Response
     */
    public function logs()
    {
        return view('adminux.components.card', [
            'header' => 'Logs',
            'body'   => nl2br(\File::get(storage_path().'/logs/laravel-'.date('Y-m-d').'.log'))
        ]);
    }

    /**
     * Display admin phpinfo
     *
     * @return \Illuminate\Http\Response
     */
    public function phpinfo()
    {
        if(isset($_GET['raw'])) {
            phpinfo();
            return;
        }

        return view('adminux.components.blank')
        ->withBody('<iframe src="admin_phpinfo?raw=1" style="height:calc(100vh - 64px);width:100%;border:none"></iframe>');
    }
}
