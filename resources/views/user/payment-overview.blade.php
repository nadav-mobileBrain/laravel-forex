@extends('layouts.user')
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
                                        <h3 class="text-uppercase font-weight-bold text-center" id="horz-layout-basic">Method - {{ $log->paymentMethod->name }}</h3>
                                        <img src="{{ asset('assets/images/payment') }}/{{ $log->paymentMethod->image }}" style="width:60%;" alt="">
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="{{ route('chose-payment-method') }}" class="btn btn-outline-info font-weight-bold text-uppercase btn-min-width mr-1 mb-1">Chose Another Method</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center" id="horz-layout-basic">{{ $page_title }}</h4>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body f-20">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <td width="50%" class="text-right">Plan Name</td>
                                        @if (Auth::user()->up_status)
                                            <td width="50%">{{ $log->user->updateplan->name }}</td>
                                        @else
                                            <td width="50%">{{ $log->user->plan->name }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="text-right">Plan Price</td>
                                        @if (Auth::user()->up_status)
                                            <td>{{ $log->user->updateplan->price }} {{ $basic->currency }}</td>
                                        @else
                                            <td>{{ $log->user->plan->price }} {{ $basic->currency }}</td>
                                        @endif
                                    </tr>
                                    @if (strtoupper($basic->currency) != $log->paymentMethod->currency)
                                        <tr>
                                            <td class="text-right">Conversion</td>
                                            <td>1 {{ $basic->currency }} = {{ $log->paymentMethod->rate }} {{ $log->paymentMethod->currency }}</td>
                                        </tr>
                                    @endif
                                    <tr class="bg-success">
                                        <td class="text-right">Total Payable</td>
                                        <td>{{ $log->usd }} {{ $log->paymentMethod->currency }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>

                            @if ($log->paymentMethod->type)
                                @include('payment.manual')
                            @else
                                @include("payment.gateway{$log->payment_id}")
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
