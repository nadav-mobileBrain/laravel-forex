@extends('layouts.user')
@section('style')
@endsection
@section('content')

    <div class="page-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h5 class="text-center">Plan Status</h5>

                    </div>
                    <div class="card-block">

                        <div class="card-body text-center">

                            <h3>Plan : {{ $user->plan->name }}</h3>
                            <br>
                            @if ($user->expire_time == 1)
                                <h3>Duration Unlimited</h3>
                            @elseif (\Carbon\Carbon::now() <= $user->expire_time)
                                @if (\Carbon\Carbon::parse($user->expire_time)->diffInDays() == 0)
                                    <h3>Duration : {{ \Carbon\Carbon::parse($user->expire_time)->diffForHumans() }} </h3>
                                @else
                                    <h3>Duration : {{ \Carbon\Carbon::parse($user->expire_time)->diffInDays() }} - Days</h3>
                                @endif
                            @else
                                <h3>Duration Expire</h3>
                            @endif
                            @if ($user->plan_status == 0)
                                <hr>
                                <a href="{{ route('chose-payment-method') }}" class="btn text-white btn-warning font-weight-bold text-uppercase btn-min-width mr-1 mb-1">Complete Payment</a>
                            @endif
                            @if ($user->up_status == 1 and $user->updateplan->price_type != 0)
                                <hr>
                                <h3>Upgrade To : {{ $user->updateplan->name }}</h3>
                                <br>
                                <a href="{{ route('chose-payment-method') }}" class="btn text-white btn-warning font-weight-bold text-uppercase btn-min-width mr-1 mb-1">Complete Payment</a>
                            @endif
                            <a href="{{ route('user-upgrade-plan') }}" class="btn text-white btn-primary font-weight-bold text-uppercase btn-min-width mr-1 mb-1">Change Plan</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h5>Signal Status</h5>
                    </div>
                    <div class="card-block">
                        <div class="card-body text-center font-weight-bold">
                            <br>
                            <h3 style="margin-bottom: 20px;">Balance : {{ $basic->symbol }}{{ $user->balance }}</h3>
                            <h3 style="margin-bottom: 32px;">Plan Total Signal : {{ $all_signal }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-blockc">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label><strong>YOUR REFERRAL LINK:</strong></label>
                                    <div class="input-group mb15">
                                        <input type="text" class="form-control input-lg" id="ref" readonly value="{{ route('auth.reference-register', $user->username) }}" />
                                        <span class="input-group-btn">
                                            <button data-clipboard-target="#ref" class="btn btn-success btn-lg clip">COPY</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label><strong>REFERRAL ID:</strong></label>
                                    <div class="input-group mb15">
                                        <input type="text" class="form-control input-lg" id="ref1" readonly value="{{ $user->username }}" />
                                        <span class="input-group-btn">
                                            <button data-clipboard-target="#ref1" class="btn btn-success btn-lg clip">COPY</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Plan Statistic</h5>
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
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <h6 class="text-center">Win Loss Pips</h6>
                                    {!! $pips->render() !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <h6 class="text-center">Last 15 days Signals</h6>
                                    {!! $line->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---ROW-->


@endsection
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
    <script>
        new ClipboardJS('.btn');
    </script>
    @stack('chartJs')
@endsection
