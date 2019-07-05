<?php

namespace App\Adminux\Admin\Controllers;

use App\Adminux\Admin\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Language $language)
    {
        if(request()->ajax()) return Datatables::of($language::query())
            ->addColumn('id2', 'adminux.components.datatables.link_show_link')
            ->rawColumns(['id2'])
            ->toJson();

        return view('adminux.components.datatables.index')->withDatatables([
            'order' => '[[ 0, "asc" ]]',
            'thead' => '<th style="min-width:30px">ID</th>
                        <th class="w-75">Language</th>
                        <th style="min-width:120px">Created At</th>',

            'columns' => '{ data: "id2", name: "id", className: "text-center" },
                          { data: "language", name: "language" },
                          { data: "created_at", name: "created_at", className: "text-center" }'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Language $language)
    {
        return view('adminux.components.create')->withModel($language)->withFields($this->getFields($language));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Language $language)
    {
        $request->validate([
            'language' => 'required|max:5|unique:'.$language->getTable(),
        ]);

        $language = $language->create($request->all());

        return redirect(route(explode('/', $request->path())[1].'.show', $language));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        return view('adminux.components.show')->withModel($language);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {
        return view('adminux.components.edit')->withModel($language)->withFields($this->getFields($language));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {
        $request->validate([
            'language'   => 'required|max:5|unique:'.$language->getTable().',language,'.$language->id,
        ]);

        $language->update($request->all());

        return redirect(route(explode('/', $request->path())[1].'.show', $language));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        $language->delete();

        return redirect(route(explode('/', request()->path())[1].'.index'));
    }

    /**
     * Build Blade edit & create form fields
     *
     * @return Array
     */
    public function getFields(Language $language)
    {
        $form = new \App\Adminux\Form($language);
        $form->addFields([
            $form->display([ 'label' => 'ID' ]),
            $form->text([ 'label' => 'Language' ]),
        ]);

        return $form->getFields();
    }
}
