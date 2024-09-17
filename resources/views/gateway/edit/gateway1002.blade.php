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
                        <h5>Edit {{ $payment->name }}</h5>
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
                        {!! Form::open(['route' => ['payment-method-update', $payment->id], 'method' => 'put', 'files' => true]) !!}

                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Display Image</strong></label>
                            <div class="col-md-12">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 290px; height: 190px;" data-trigger="fileinput">
                                        <img style="width: 290px" src="{{ asset('assets/images/payment') }}/{{ $payment->image }}" alt="...">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 290px; max-height: 190px"></div>
                                    <div>
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new bold uppercase"><i class="fa fa-file-image-o"></i> Select image</span>
                                            <span class="fileinput-exists bold uppercase"><i class="fa fa-edit"></i> Change</span>
                                            <input type="file" name="image" accept="image/*">
                                        </span>
                                        <a href="#" class="btn btn-danger fileinput-exists bold uppercase" data-dismiss="fileinput"><i class="fa fa-trash"></i> Remove</a>
                                    </div>
                                    <b style="color: red;">Image Type PNG,JPEG,JPG. Resize - (290X190)</b><br>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Display Name</strong></label>
                            <div class="col-md-12">
                                <input class="form-control" name="name" value="{{ $payment->name }}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">ACCEPT CURRENCY</strong></label>
                            <div class="col-md-12">
                                <select name="currency" id="currency" class="form-control">
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency['cc'] }}" {{ $currency['cc'] == $payment->currency ? 'selected' : '' }}>{{ $currency['cc'] }} - {{ $currency['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Conversion Rate</strong></label>
                            <div class="col-md-12">
                                <div class="input-group mb15">
                                    <span class="input-group-addon"><strong>1 {{ $basic->currency }} = </strong></span>
                                    <input class="form-control" name="rate" value="{{ $payment->rate }}" type="text" required>
                                    <span class="input-group-addon"><strong id="updateCurrency">{{ $payment->currency }}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Mollie Api key</strong></label>
                            <div class="col-md-12">
                                <div class="input-group mb15">
                                    <input class="form-control" name="val1" value="{{ $payment->val1 }}" type="text">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">STATUS</strong></label>
                            <div class="col-md-12">
                                <input data-toggle="toggle" {{ $payment->status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="Activate" data-off="Deactivate" data-width="100%" type="checkbox" name="status">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> UPDATE</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
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
        $('#currency').on('change', function() {
            $('#updateCurrency').text(this.value);
        });
    </script>
@stop
