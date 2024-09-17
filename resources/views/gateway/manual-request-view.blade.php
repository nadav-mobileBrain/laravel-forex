@extends('layouts.dashboard')
@section('style')

    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.fancybox.min.css') }}" />
    <script src="{{ asset('assets/admin/js/jquery.fancybox.min.js') }}"></script>

@endsection
@section('content')

    <div class="page-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <div class="card box-shadow-0">
                                <div class="card-content collpase show">
                                    <div class="card-body text-center">
                                        <h3 class="text-uppercase font-weight-bold text-center" id="horz-layout-basic">{{$payment->paymentMethod->name}}</h3>
                                        <hr >
                                        <div class="text-center">
                                            <h5>Plan Name : {{ $payment->plan->name }}</h5>
                                            <h5>Plan Price : {{ $payment->plan->price }} {{ $basic->currency }}</h5>
                                        </div>
                                        <hr>
                                        <img src="{{ asset('assets/images/payment') }}/{{$payment->paymentMethod->image}}" style="width:100%;" alt="">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <div class="card box-shadow-0">
                                <div class="card-content collpase show">
                                    <div class="card-body">
                                        <h3 class="text-uppercase font-weight-bold text-center" id="horz-layout-basic">Payment Details</h3>
                                        <hr>
                                        <h5>Payment Prove Image : </h5>
                                        <br>
                                        <div class="row">
                                            @foreach($payment->paymentLogImage as $pm)
                                            <div class="col-md-3">
                                                <a data-fancybox="gallery" href="{{ asset('assets/images/paymentimage') }}/{{$pm->name}}">
                                                    <img src="{{ asset('assets/images/paymentimage') }}/{{$pm->name}}" style="width: 100%" alt="">
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                        <hr >
                                        <h5>Message : </h5>
                                        <br>
                                        <p>{!! $payment->message !!}</p>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="button" {{ $payment->status == 1 ? 'disabled' : '' }} class="btn btn-danger btn-block btn-lg bold font-weight-bold delete_button"
                                                        data-toggle="modal" data-target="#DelModal"
                                                        data-id="{{ $payment->id }}" title="Cancel Payment">
                                                    <i class='fa fa-times'></i> cancel Payment
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" {{ $payment->status == 1 ? 'disabled' : '' }} class="btn btn-success btn-block btn-lg bold font-weight-bold confirm_button"
                                                        data-toggle="modal" data-target="#ConModal"
                                                        data-id="{{ $payment->id }}" title="Confirm Payment">
                                                    <i class='fa fa-send'></i> Confirm Payment
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ConModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Confirm This Payment ?</strong>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('manual-payment-request-confirm') }}" class="form-inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" class="confirm_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Yes Sure.!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Cancel This Payment ?</strong>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('manual-payment-request-cancel') }}" class="form-inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" class="delete_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Yes Sure.!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                $(".delete_id").val(id);
            });
            $(document).on("click", '.confirm_button', function (e) {
                var id = $(this).data('id');
                $(".confirm_id").val(id);
            });
        });
    </script>
@endsection