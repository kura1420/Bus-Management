@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <form action="{{ route('admin.user-admin.update', $admin->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Name')</label>
                                <input type="text" class="form-control" placeholder="@lang('Enter Admin Name')" name="name" required value="{{$admin->name}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Email')</label>
                                <input type="email" class="form-control" placeholder="@lang('Enter Admin Email')" name="email" required value="{{$admin->email}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Username')</label>
                                <input type="text" class="form-control" placeholder="@lang('Enter Admin Username')" name="username" readonly value="{{$admin->username}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Role')</label>
                                <select name="role" id="role" class="form-control">
                                    @foreach($roles as $role)
                                        @if (count($admin->roles)>0)
                                        <option value="{{$role->id}}" {{$role->id == $admin->roles[0]->id ? 'selected' : ''}}>{{$role->name}}</option>
                                        @else 
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Status')</label>
                                <select name="active" id="active" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">No Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary">@lang('Save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
<a href="{{route('admin.user-admin.index')}}" class="btn btn-sm btn--secondary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-chevron-left"></i>@lang('Back')</a>
@endpush

@push('style')

@endpush

@push('script')

@endpush