@extends('adminux.layout')

@section('title', __('adminux.create').' '.(new ReflectionClass($model))->getShortName())

@section('body')
<form method="post" action="{{ str_replace('/create', '', Request::url()) }}">
    @csrf
    <div class="card mt-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h5 class="mb-0">{{ __('adminux.create') }} {{(new ReflectionClass($model))->getShortName()}}</h5>
                <div>
                    <button type="submit" class="btn btn-primary btn-sm my-n1"><span class="feather-adminux" data-feather="save"></span> Create</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('adminux.inc.errors')
            @foreach($fields as $val)
                {!! $val !!}
            @endforeach
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary"><span class="feather-adminux" data-feather="save"></span> Create</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
