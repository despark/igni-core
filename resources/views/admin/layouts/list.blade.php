@extends('ignicms::admin.layouts.default')

@section('pageTitle', $pageTitle)

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ $pageTitle }}</h3>
                </div>

                <div class="box-body">
                    <div id="data-table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        @if(isset($createRoute))
                            <a href="{{ route($createRoute) }}"
                               class="btn btn-success pull-left">+ {{ trans('ignicms::admin.add') }} {{ $pageTitle }}</a>
                        @endif
                        <div class="row">
                            <div class="col-sm-12" style="overflow: auto">
                                <table id="data-table" class="table table-bordered table-striped dataTable"
                                       role="grid" aria-describedby="data-table_info">
                                    <thead>
                                    <tr>
                                        @foreach($controller->getDataTableColumns() as $col)
                                            <th class="col-{{ $col['data'] }}">{{ $col['title'] or $col['data'] }}</th>
                                        @endforeach
                                        @if($controller->hasActionButtons())
                                            <th class="no-sort actions-col">{{ trans('ignicms::admin.actions') }}</th>
                                        @endif
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($destroyRoute))
        <div class="modal modal-danger fade" id="delete-modal" tabindex="-1" role="dialog"
             aria-labelledby="deleteModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-target="#delete-modal" data-dismiss="modal"
                                aria-label="{{ trans('ignicms::admin.close') }}"><span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">{{ trans('ignicms::admin.deleteTitle') }}</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            {{ trans('ignicms::admin.deleteConfirm') }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-target="#delete-modal"
                                data-dismiss="modal">{{ trans('ignicms::admin.close') }}</button>
                        <form method="POST" action="" class="delete-form">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}"/>
                            <input type="hidden" name="_method" value="DELETE"/>

                            <button type="submit" type="button" class="delete-btn btn btn-outline">
                                {{ trans('ignicms::admin.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@push('additionalScripts')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-XSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

    // Sortable
    var changePosition = function (requestData) {
        $.ajax({
            url: '/sort',
            type: 'POST',
            data: requestData,
            success: function (data) {
                if (data.success) {
                    console.log('Sort: success!');
                } else {
                    console.log(data.errors);
                }
            },
            error: function (e) {
                console.log('Something went wrong! Error(' + e.status + '): ' + e.statusText);
            }
        });
    };

    var $sortableTable = $('.sortable');
    if ($sortableTable.length > 0) {
        $sortableTable.sortable({
            handle: '.sortable-handle',
            axis: 'y',
            update: function (a, b) {
                var entityName = $(this).data('entityname');
                var $sorted = b.item;

                var $previous = $sorted.prev();
                var $next = $sorted.next();

                if ($previous.length > 0) {
                    changePosition({
                        parentId: $sorted.data('parentid'),
                        type: 'moveAfter',
                        entityName: entityName,
                        id: $sorted.data('itemid'),
                        positionEntityId: $previous.data('itemid')
                    });
                } else if ($next.length > 0) {
                    changePosition({
                        parentId: $sorted.data('parentid'),
                        type: 'moveBefore',
                        entityName: entityName,
                        id: $sorted.data('itemid'),
                        positionEntityId: $next.data('itemid')
                    });
                } else {
                    console.log(a);
                }
            },
            cursor: "move"
        });
    }

    // Delete entity
    $('body').on('click', '.js-open-delete-modal', function (e) {
        e.preventDefault();
        var that = $(this),
            $deleteModal = $('#delete-modal'),
            deleteURL = that.data('delete-url');

        $deleteModal.find('.delete-form').attr('action', deleteURL);

        $deleteModal.modal();
    });

    var isSortable = $('th.sort').length === 0;

    var table = $('#data-table').DataTable({
        paging: isSortable !== false,
        pageLength: {{ config('ignicms.paginateLimit') }},
        lengthChange: false,
        searching: true,
        ordering: isSortable !== false,
        info: false,
        autoWidth: true,
        processing: true,
        serverSide: true,
        ajax: "{{ $dataTablesAjaxUrl }}",
        columns: [
                @foreach ($controller->getDataTableColumns() as $data)
            {
                data: '{{ $data['data'] }}',
                name: '{{ $data['name'] }}'
                @if(isset($data['title'])), title: '{{$data['title']}}'@endif,
                defaultContent: "",
                render: function(data, type, full, meta) {
                    if (data === 1) {
                        return 'Yes';
                    } else if (data === 0) {
                        return 'No';
                    }

                    return data;
                }
            },
                @endforeach
                @if($controller->hasActionButtons())
            {
                data: 'action', name: 'action', orderable: false, searchable: false
            }
            @endif
        ],
        columnDefs: [
            {
                targets: "no-sort",
                orderable: false,
                searchable: false
            }
        ],
        aaSorting: [],
        oLanguage: {
            sSearch: "<span class='search-label uppercase'>Search</span>"
        }
    });
</script>
@endpush
