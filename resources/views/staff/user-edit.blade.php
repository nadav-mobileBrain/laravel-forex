@extends('layouts.staff')
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

                        <form class="form form-horizontal" action="{{ route('staff-user-update') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            {{ csrf_field() }}
                            <div class="form-body">

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Name : </b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="projectinput1" class="form-control font-weight-bold" value="{{ $user->name }}" placeholder="Name" name="name" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>User Name : </b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="projectinput1" class="form-control font-weight-bold" value="{{ $user->username }}" placeholder="User Name" name="username" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Email : </b></label>
                                    <div class="col-md-8">
                                        <input type="email" id="projectinput1" class="form-control font-weight-bold" value="{{ $user->email }}" placeholder="Email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Country Code : </b></label>
                                    <div class="col-md-8">
                                        <select name="country_code" id="country_code" required class="form-control font-weight-bold">
                                            @foreach ($country as $cn)
                                                <option value="{{ $cn['dial_code'] }}">{{ $cn['name'] }} ({{ $cn['dial_code'] }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Phone : </b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="projectinput1" class="form-control font-weight-bold" value="{{ $user->phone }}" placeholder="Phone" name="phone" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Whatsapp ID : </b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="projectinput1" class="form-control font-weight-bold" value="{{ $user->whatsapp_id }}" placeholder="{{ $user->whatsapp_id ? $user->whatsapp_id : 'NOT ACTIVATE YET' }}" name="whatsapp_id" readonly required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Reset Whatsapp : </b></label>
                                    <div class="col-md-8">
                                        <input data-toggle="toggle" data-onstyle="danger" data-offstyle="info" data-on="RESET WHATSAPP" data-off="DON'T RESET" data-width="100%" type="checkbox" name="whatsapp_status">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Telegram ID : </b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="projectinput1" class="form-control font-weight-bold" value="{{ $user->telegram_id }}" placeholder="{{ $user->telegram_id ? $user->telegram_id : 'NOT ACTIVATE YET' }}" name="telegram_id" readonly required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Reset Telegram : </b></label>
                                    <div class="col-md-8">
                                        <input data-toggle="toggle" data-onstyle="danger" data-offstyle="info" data-on="RESET TELEGRAM" data-off="DON'T RESET" data-width="100%" type="checkbox" name="telegram_status">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Select Plan : </b></label>
                                    <div class="col-md-8">
                                        <select name="plan_id" id="" required class="form-control font-weight-bold">
                                            <option value="" class="font-weight-bold">Select One</option>
                                            @foreach ($plan as $p)
                                                @if ($p->id == $user->plan_id)
                                                    <option value="{{ $p->id }}" class="font-weight-bold" selected>{{ $p->name }} - @if ($p->price == 0)
                                                            FREE
                                                        @else
                                                            {{ $p->price }} {{ $basic->currency }}
                                                        @endif
                                                    </option>
                                                @else
                                                    <option value="{{ $p->id }}" class="font-weight-bold">{{ $p->name }} - @if ($p->price == 0)
                                                            FREE
                                                        @else
                                                            {{ $p->price }} {{ $basic->currency }}
                                                        @endif
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Plan Expire Date : </b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="datetimepicker1" class="form-control font-weight-bold" value="{{ $user->expire_time }}" placeholder="Plan Expire Date" name="expire_time" required>
                                        <strong><code>Date Format : YYYY-MM-DD HH:mm:ss </code> <code> 1 means duration unlimited</code></strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control text-right" for="projectinput1"><b>Payment Status : </b></label>
                                    <div class="col-md-8">
                                        <input data-toggle="toggle" {{ $user->plan_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="Complete" data-off="Not Complete" data-width="100%" type="checkbox" name="plan_status">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-8 offset-3">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="ft-navigation"></i> Update User</button>
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
    <script src="{{ asset('assets/admin/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var coder = '{{ $user->country_code }}';
            $('#country_code').val(coder);
        });
    </script>
@endsection
