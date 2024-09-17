@extends('layouts.dashboard')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-colorpicker.min.css') }}">
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

                        {!! Form::model($basic, ['route' => ['basic-update', $basic->id], 'method' => 'PUT', 'class' => 'form form-horizontal']) !!}
                        <div class="form-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class=""><strong style="text-transform: uppercase;">Website Title</strong></label>
                                    <div class="input-group">
                                        <input class="form-control bold input-lg" name="title" value="{{ $basic->title }}" type="text" required>
                                        <span class="input-group-addon"><strong><i class="fa fa-file-text-o"></i></strong></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class=""><strong style="text-transform: uppercase;">Base Web Color</strong></label>
                                    <div class="input-group">
                                        <input type="text" name="color" class="form-control" style="background-color: #{{ $basic->color }};color: #fff" value="{{ $basic->color }}">
                                        <span class="input-group-addon"><strong><i class="fa fa-paint-brush"></i></strong></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class=""><strong style="text-transform: uppercase;">Base Currency</strong></label>
                                    <div class="input-group">
                                        <input class="form-control bold input-lg" name="currency" value="{{ $basic->currency }}" type="text" required>
                                        <span class="input-group-addon"><strong><i class="fa fa-money"></i></strong></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class=""><strong style="text-transform: uppercase;">Currency Symbol</strong></label>
                                    <div class="input-group">
                                        <input class="form-control bold input-lg" name="symbol" value="{{ $basic->symbol }}" type="text" required>
                                        <span class="input-group-addon"><strong><i class="fa fa-money"></i></strong></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class=""><strong style="text-transform: uppercase;">Whatsapp Notification</strong></label>
                                    <input data-toggle="toggle" {{ $basic->whatsapp_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="ON" data-off="OFF" data-width="100%" type="checkbox" name="whatsapp_status">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class=""><strong style="text-transform: uppercase;">Telegram Notification</strong></label>
                                    <input data-toggle="toggle" {{ $basic->telegram_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="ON" data-off="OFF" data-width="100%" type="checkbox" name="telegram_status">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class=""><strong style="text-transform: uppercase;">Email Notification</strong></label>
                                    <input data-toggle="toggle" {{ $basic->email_alert == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="ENABLE" data-off="DISABLE" data-width="100%" type="checkbox" name="email_alert">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class=""><strong style="text-transform: uppercase;">Phone Notification</strong></label>
                                    <input data-toggle="toggle" {{ $basic->phone_alert == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="ENABLE" data-off="DISABLE" data-width="100%" type="checkbox" name="phone_alert">
                                </div>
                                <div class="form-group col-md-6">
                                    <label><strong style="text-transform: uppercase;">User Email Verify</strong></label>
                                    <input data-toggle="toggle" {{ $basic->email_verify == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="ENABLE" data-off="DISABLE" data-width="100%" type="checkbox" name="email_verify">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class=""><strong style="text-transform: uppercase;">User Phone Verify</strong></label>
                                    <div class="">
                                        <input data-toggle="toggle" {{ $basic->phone_verify == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="ENABLE" data-off="DISABLE" data-width="100%" type="checkbox" name="phone_verify">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class=""><strong style="text-transform: uppercase;">Contact Phone</strong></label>
                                    <div class="">
                                        <div class="input-group">
                                            <input type="text" name="phone" class="form-control bold input-lg" value="{{ $basic->phone }}" required>
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class=""><strong style="text-transform: uppercase;">Contact Email</strong></label>
                                    <div class="">
                                        <div class="input-group">
                                            <input type="text" name="email" class="form-control bold input-lg" value="{{ $basic->email }}" required>
                                            <span class="input-group-addon"><i class="fa fa-envelope-open-o"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class=""><strong style="text-transform: uppercase;">Web Author</strong></label>
                                    <div class="">
                                        <textarea name="author" class="form-control bold input-lg" id="" cols="30" rows="3">{{ $basic->author }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class=""><strong style="text-transform: uppercase;">Contact Address</strong></label>
                                    <div class="">
                                        <textarea name="address" id="" class="form-control bold input-lg" cols="30" rows="3">{{ $basic->address }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class=""><strong style="text-transform: uppercase;">Web Meta Tag</strong></label>
                                    <div class="">
                                        <textarea name="meta_tag" class="form-control bold input-lg" id="" cols="30" rows="3">{{ $basic->meta_tag }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class=""><strong style="text-transform: uppercase;">Web Description</strong></label>
                                    <div class="">
                                        <textarea name="description" id="" class="form-control bold input-lg" cols="30" rows="3">{{ $basic->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary bg-softwarezon-x btn-block btn-lg"><i class="fa fa-send"></i> UPDATE</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('.my-colorpicker1').colorpicker()
        });
    </script>
@endsection
