@extends('layouts.dashboard')
@section('style')
    <link href="{{ asset('assets/admin/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $page_title }}</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-chevron-left"></i></li>
                                <li><i class="fa fa-window-maximize full-card"></i></li>
                                <li><i class="fa fa-minus minimize-card"></i></li>
                                <li><i class="fa fa-times close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        <form class="form form-horizontal" action="{{ route('staff-update') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $staff->id }}">
                            <div class="form-body">

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Name : </b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="projectinput1" class="form-control font-weight-bold" value="{{ $staff->name }}" placeholder="Name" name="name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Email : </b></label>
                                    <div class="col-md-8">
                                        <input type="email" id="projectinput1" class="form-control font-weight-bold" value="{{ $staff->email }}" placeholder="Email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Phone : </b></label>
                                    <div class="col-md-3">
                                        <select name="country_code" id="" required class="form-control font-weight-bold">
                                            @foreach ($country as $cn)
                                                <option value="{{ $cn['dial_code'] }}" {{ $cn['dial_code'] == $staff->country_code ? 'selected' : '' }}>{{ $cn['name'] }} ({{ $cn['dial_code'] }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" id="projectinput1" class="form-control font-weight-bold" value="{{ $staff->phone }}" placeholder="Phone" name="phone" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Status : </b></label>
                                    <div class="col-md-8">
                                        <input {{ $staff->status == 1 ? 'checked' : '' }} data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="Deactive" data-width="100%" type="checkbox" name="status">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Access : </b></label>
                                    <div class="col-md-8">
                                        <div class="form-check ml-4">
                                            <input class="form-check-input" name="permissions[]" {{ in_array('manage-signal', $permissions) ? 'checked' : '' }} type="checkbox" value="manage-signal" id="manage-signal">
                                            <label class="form-check-label pl-0" for="manage-signal">Manage Signal</label>
                                        </div>
                                        <div class="form-check ml-4">
                                            <input class="form-check-input" name="permissions[]" {{ in_array('manage-post', $permissions) ? 'checked' : '' }} type="checkbox" value="manage-post" id="manage-post">
                                            <label class="form-check-label pl-0" for="manage-post">Manage Post</label>
                                        </div>
                                        <div class="form-check ml-4">
                                            <input class="form-check-input" name="permissions[]" {{ in_array('manage-user', $permissions) ? 'checked' : '' }} type="checkbox" value="manage-user" id="manage-user">
                                            <label class="form-check-label pl-0" for="manage-user">Manage User</label>
                                        </div>
                                        <div class="form-check ml-4">
                                            <input class="form-check-input" name="permissions[]" {{ in_array('manage-payment', $permissions) ? 'checked' : '' }} type="checkbox" value="manage-payment" id="manage-payment">
                                            <label class="form-check-label pl-0" for="manage-payment">Manage Manual Payment</label>
                                        </div>
                                        <div class="form-check ml-4">
                                            <input class="form-check-input" name="permissions[]" {{ in_array('manage-withdraw', $permissions) ? 'checked' : '' }} type="checkbox" value="manage-withdraw" id="manage-withdraw">
                                            <label class="form-check-label pl-0" for="manage-withdraw">Manage Withdraw</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-8 offset-3">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Update Staff</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>
@endsection
