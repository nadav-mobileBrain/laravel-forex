@extends('layouts.dashboard')
@section('style')
    <link href="{{ asset('assets/admin/css/bootstrap-fileinput.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Edit {{ $gateway->name }}</h5>
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

                        {!! Form::open(['route' => ['update-sms-gateway', $gateway->id], 'method' => 'put', 'files' => true]) !!}
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Gateway Logo</strong></label>
                            <div class="col-md-12">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 192px; height: 144px;" data-trigger="fileinput">
                                        <img style="width: 192px" src="{{ asset('assets/images/settings/sms') }}/{{ $gateway->image }}" alt="...">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 192px; max-height: 144px"></div>
                                    <div>
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new bold uppercase"><i class="fa fa-file-image-o"></i> Select image</span>
                                            <span class="fileinput-exists bold uppercase"><i class="fa fa-edit"></i> Change</span>
                                            <input type="file" name="image" accept="image/*">
                                        </span>
                                        <a href="#" class="btn btn-danger fileinput-exists bold uppercase" data-dismiss="fileinput"><i class="fa fa-trash"></i> Remove</a>
                                    </div>
                                    <b style="color: red;">Image Type PNG,JPEG,JPG. Resize - (192X144)</b><br>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Gateway Name</strong></label>
                            <div class="col-md-12">
                                <input class="form-control" name="name" value="{{ $gateway->name }}" type="text" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Username</strong></label>
                            <div class="col-md-12">
                                <div class="input-group mb15">
                                    <input class="form-control" name="data[username]" value="{{ $gateway->data ? $gateway->data['username'] : '' }}" required type="text">
                                    <span class="input-group-addon"><b><i class="fa fa-code"></i></b></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Password</strong></label>
                            <div class="col-md-12">
                                <div class="input-group mb15">
                                    <input class="form-control" name="data[password]" value="{{ $gateway->data ? $gateway->data['password'] : '' }}" required type="text">
                                    <span class="input-group-addon"><b><i class="fa fa-code"></i></b></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">From Number</strong></label>
                            <div class="col-md-12">
                                <div class="input-group mb15">
                                    <input class="form-control" name="data[from]" value="{{ $gateway->data ? $gateway->data['from'] : '' }}" required type="text">
                                    <span class="input-group-addon"><b><i class="fa fa-code"></i></b></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">STATUS</strong></label>
                            <div class="col-md-12">
                                <input data-toggle="toggle" {{ $gateway->status == 1 ? 'checked' : '' }} data-onstyle="success" data-on="Activate" data-off="Deactivate" data-offstyle="danger" data-width="100%" type="checkbox" name="status">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button type="button" data-toggle="modal" data-target="#TestModal" class="btn btn-success btn-block btn-lg"><i class="fa fa-send"></i> SEND TEST SMS</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> UPDATE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('settings.sms.__testModal')


@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/bootstrap-fileinput.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>
@stop
