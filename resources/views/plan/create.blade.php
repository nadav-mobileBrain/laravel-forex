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

                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::open(['route' => 'plan-create', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal', 'files' => true]) !!}
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label"><b>Plan Name :</b> </label>
                                        <div class="col-sm-12">
                                            <input name="name" value="" class="form-control input-lg" type="text" required placeholder="Plan Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label"><b>Plan Duration Type : </b></label>
                                        <div class="col-sm-12">
                                            <select name="plan_type" id="plan_type" class="form-control input-lg font-weight-bold">
                                                <option value="" class="font-weight-bold">Select One</option>
                                                <option value="0" class="font-weight-bold">Limited</option>
                                                <option value="1" class="font-weight-bold">Unlimited</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="duration" style="display: none">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label"><b>Duration : </b></label>
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <input name="duration" value="" class="form-control input-lg" type="text" placeholder="Duration">
                                                    <span class="input-group-addon"><strong>Days</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label"><b>Price Type : </b></label>
                                        <div class="col-sm-12">
                                            <select name="price_type" id="price_type" class="form-control input-lg font-weight-bold">
                                                <option value="" class="font-weight-bold">Select One</option>
                                                <option value="0" class="font-weight-bold">FREE Plan</option>
                                                <option value="1" class="font-weight-bold">PAID Plan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="price" style="display: none">
                                        <label class="col-sm-12 control-label"><b>Plan Price : </b></label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <input name="price" value="" class="form-control input-lg" type="number" step="0.001" placeholder="Plan Price">
                                                <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label"><b>Support : </b></label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <input name="support" value="" class="form-control input-lg" type="text" required placeholder="Support">
                                                <span class="input-group-addon"><strong><i class="fa fa-question-circle"></i></strong></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label"><b>Dashboard Signal : </b></label>
                                        <div class="col-md-12">
                                            <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="dashboard_status">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label"><b>Whatsapp Alert : </b></label>
                                        <div class="col-md-12">
                                            <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="whatsapp_status">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label"><b>Telegram Alert : </b></label>
                                        <div class="col-md-12">
                                            <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="telegram_status">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label"><b>Email Alert : </b></label>
                                        <div class="col-md-12">
                                            <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="email_status">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label"><b>SMS Alert : </b></label>
                                        <div class="col-md-12">
                                            <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="sms_status">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label"><b>Phone Consulting : </b></label>
                                        <div class="col-md-12">
                                            <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="call_status">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table table-bordered table-stripe">
                                            <thead>
                                                <tr>
                                                    <th width="80%">Plan Text</th>
                                                    <th>
                                                        <button type="button" class="btn btn-sm btn-primary btn-mini addRow"><i class="fa fa-plus"></i> Add More</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="contents[]" class="form-control" id="" placeholder="Write your Plan Text" required />
                                                    </td>
                                                    {{--  <td><button type="button" class="btn btn-sm btn-primary addRow"><i class="fa fa-plus"></i> Add More</button></td>  --}}
                                                    <th><button type="button" class="btn btn-sm btn-danger deleteRow"><i class="fa fa-times"></i> Remove</button></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12 control-label"><b>Publication Status : </b></label>
                                        <div class="col-md-12">
                                            <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="status">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block "> <i class="fa fa-send"></i> Create New Plan</button>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on("change", '#plan_type', function(e) {
                var type = $(this).val();
                if (type == "0") {
                    var duration = document.getElementById('duration');
                    duration.style.display = 'block';
                } else if (type == "1") {
                    var duration = document.getElementById('duration');
                    duration.style.display = 'none';
                }
            });
            $(document).on("change", '#price_type', function(e) {
                var type = $(this).val();
                if (type == "0") {
                    var duration = document.getElementById('price');
                    duration.style.display = 'none';
                } else if (type == "1") {
                    var duration = document.getElementById('price');
                    duration.style.display = 'block';
                }
            });
            $('.addRow').on('click', function(e) {
                var row = '<tr>';
                row += '<td><input type="text" name="contents[]" class="form-control" id="" placeholder="Write your Plan Text" required /></td>';
                row += '<th><button type="button" class="btn btn-sm btn-danger deleteRow"><i class="fa fa-times"></i> Remove</button></th>';
                row += '</tr>';
                $('tbody').append(row);
            });

            $('tbody').on('click', '.deleteRow', function() {
                $(this).parent().parent().remove();
            });
        });
    </script>
@endsection
