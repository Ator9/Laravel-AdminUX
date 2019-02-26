@extends('adminux.components.layout.layout')

@section('title', 'Partners' . ' - ' . config('app.name', 'Admin'))

@section('head')
<link href="{{ asset('adminux/resources/libs/jquery.dataTables.css') }}" rel="stylesheet">
<style>
html,body,.container-fluid,.container-fluid .row{height:100%}
.table-responsive{height:calc(100% - 3px);height:-moz-calc(100% - 3px);height:-webkit-calc(100% - 3px)}
#datatable_filter input{margin-left:0}
</style>
@endsection

@section('body')
<div class="table-responsive pt-3">
    <table class="table table-striped table-hover text-nowrap compact" id="datatable">
        <thead>
            <tr>
                {!! $datatables['thead'] !!}
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('scripts')
<script src="{{ asset('adminux/resources/libs/jquery.dataTables.js') }}"></script>
<script src="{{ asset('adminux/resources/libs/dataTables.pageResize.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#datatable').DataTable({
        scrollResize: true,
        scrollY: '100vh',
        scrollCollapse: true,
        pageLength: @isset ($datatables['pageLength']) {{ $datatables['pageLength'] }} @else {{ 50 }} @endisset,
        ajax: '{{ Request::url() }}?datatables=1',
        serverSide: true,
        processing: true,
        columns: [ {!! $datatables['columns'] !!} ],
        order: @isset ($datatables['order']) {{ $datatables['order'] }} @else [[ 0, 'desc' ]] @endisset,
        dom: @isset ($datatables['dom']) '{{ $datatables['dom'] }}' @else '<"float-left"f>rtip' @endisset,
        language: {
           search: '',
           searchPlaceholder: 'Search... '
        },
    });
});
</script>
@endsection
