@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two" id="myTable">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td>{{$admin->name}}</td>
                                <td>{{$admin->email}}</td>
                                <td>{{$admin->username}}</td>
                                <td>{{$admin->active_text}}</td>

                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.user-admin.edit', $admin->id) }}" class="icon-btn ml-1" data-toggle="tooltip" title="" data-original-title="@lang('Update')">
                                        <i class="las la-pen text--shadow"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
<a href="{{route('admin.user-admin.create')}}" class="btn btn-sm btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
@endpush

@push('script')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
@endpush