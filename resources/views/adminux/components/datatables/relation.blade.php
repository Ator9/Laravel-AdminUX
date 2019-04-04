@section('head')
<link href="{{ asset('adminux/resources/libs/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<style>
.table thead th{border-bottom-width:0}
#datatable_filter input{margin-left:3px;margin-top:3px}
</style>
@endsection

<div class="card my-3">
    <div class="card-header">
        <h5 class="mb-0">{{ ucfirst($datatables['model']->getRelationName()) }}</h5>
    </div>
    <div class="card-body">
        @include('adminux.components.layout.errors')
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="datatable">
                <thead>
                    <tr>
                        {!! $datatables['thead'] !!}
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('adminux/resources/libs/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminux/resources/libs/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    var table = $('#datatable').DataTable({
        scrollCollapse: true,
        pageLength: @isset($datatables['pageLength']) {{ $datatables['pageLength'] }} @else {{ 50 }} @endisset,
        ajax: '{{ Request::url() }}?datatables=1',
        serverSide: true,
        processing: true,
        columns: [ {!! $datatables['columns'] !!} ],
        order: @isset($datatables['order']) {{ $datatables['order'] }} @else [[ 0, 'desc' ]] @endisset,
        dom: @isset($datatables['dom']) '{{ $datatables['dom'] }}' @else '<"float-left"f>rt<"float-left"i>p' @endisset,
        language: {
           search: '',
           searchPlaceholder: '{{ __('adminux.add') }}...'
       }
    });

    @isset($datatables['customCode']) {{ $datatables['customCode'] }} @endisset
});
</script>
@endsection
