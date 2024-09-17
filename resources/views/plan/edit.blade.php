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
                                <form action="{{ route('plan-update', $plan->id) }}" method="post" role="form" class="form-horizontal">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label"><b>Plan Name : </b></label>
                                            <div class="col-sm-12">
                                                <input name="name" value="{{ $plan->name }}" class="form-control input-lg" type="text" required placeholder="Plan Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label"><b>Plan Type : </b></label>
                                            <div class="col-sm-12">
                                                <select name="plan_type" id="plan_type" class="form-control input-lg font-weight-bold">
                                                    <option value="0" {{ $plan->plan_type == 0 ? 'selected' : '' }} class="font-weight-bold">Limited</option>
                                                    <option value="1" {{ $plan->plan_type == 1 ? 'selected' : '' }} class="font-weight-bold">Unlimited</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="duration" style="display: {{ $plan->plan_type == 0 ? 'block' : 'none' }}">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label"><b>Duration : </b></label>
                                                <div class="col-sm-12">
                                                    <div class="input-group">
                                                        <input name="duration" value="{{ $plan->duration }}" class="form-control input-lg" type="number" placeholder="Duration">
                                                        <span class="input-group-addon"><strong>Days</strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label"><b>Price Type : </b></label>
                                            <div class="col-sm-12">
                                                <select name="price_type" id="price_type" class="form-control input-lg font-weight-bold">
                                                    <option value="0" {{ $plan->price_type == 0 ? 'selected' : '' }} class="font-weight-bold">FREE Plan</option>
                                                    <option value="1" {{ $plan->price_type == 1 ? 'selected' : '' }} class="font-weight-bold">PAID Plan</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" id="price" style="display: {{ $plan->price_type == 0 ? 'none' : 'block' }}">
                                            <label class="col-sm-12 control-label"><b>Plan Price : </b></label>
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <input name="price" value="{{ $plan->price }}" class="form-control input-lg" type="number" placeholder="Plan Price">
                                                    <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label"><b>Support : </b></label>
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <input name="support" value="{{ $plan->support }}" class="form-control input-lg" type="text" required placeholder="Support">
                                                    <span class="input-group-addon"><strong><i class="fa fa-question-circle"></i></strong></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label"><b>Dashboard Signal : </b></label>
                                            <div class="col-md-12">
                                                <input data-toggle="toggle" {{ $plan->dashboard_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="dashboard_status">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label"><b>Whatsapp Alert : </b></label>
                                            <div class="col-md-12">
                                                <input data-toggle="toggle" {{ $plan->whatsapp_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="whatsapp_status">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label"><b>Telegram Status </b></label>
                                            <div class="col-md-12">
                                                <input data-toggle="toggle" {{ $plan->telegram_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="telegram_status">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label"><b>Email Alert : </b></label>
                                            <div class="col-md-12">
                                                <input data-toggle="toggle" {{ $plan->email_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="email_status">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label"><b>SMS Alert : </b></label>
                                            <div class="col-md-12">
                                                <input data-toggle="toggle" {{ $plan->sms_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="sms_status">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label"><b>Phone Consulting : </b></label>
                                            <div class="col-md-12">
                                                <input data-toggle="toggle" {{ $plan->call_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="call_status">
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
                                                    @if ($plan->contents)
                                                        @foreach ($plan->contents as $content)
                                                            <tr>
                                                                <td>
                                                                    <input type="text" name="contents[]" value="{{ $content }}" class="form-control" id="" placeholder="Write your Plan Text" required />
                                                                </td>
                                                                <th><button type="button" class="btn btn-sm btn-danger deleteRow"><i class="fa fa-times"></i> Remove</button></th>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label"><b>Publication Status </b></label>
                                            <div class="col-md-12">
                                                <input data-toggle="toggle" {{ $plan->status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="YES" data-off="NO" data-width="100%" type="checkbox" name="status">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-12 col-md-12">
                                                <button type="submit" class="btn btn-primary btn-lg btn-block "> <i class="fa fa-send"></i> Update Plan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/bootstrap-fileinput.js') }}"></script>
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
