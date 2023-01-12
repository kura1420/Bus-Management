@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <form action="{{ route('admin.user-admin.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Name')</label>
                                <input type="text" class="form-control" placeholder="@lang('Enter Admin Name')" name="name" required value="{{old('name')}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Email')</label>
                                <input type="email" class="form-control" placeholder="@lang('Enter Admin Email')" name="email" required value="{{old('email')}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Username')</label>
                                <input type="text" class="form-control" placeholder="@lang('Enter Admin Username')" name="username" required value="{{old('username')}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Password')</label>
                                <input type="password" class="form-control" placeholder="@lang('Enter Admin Password')" name="password" required>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Role')</label>
                                <select name="role" id="role" class="form-control">
                                    @foreach($roles as $role)
                                    <option value="{{$role->name}}" {{old('role') == $role->name ? 'selected' : ''}}>{{$role->name}}</option>
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