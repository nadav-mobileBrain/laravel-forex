@extends('layouts.dashboard')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-green">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $win_pips }}+</h3>
                                <span class="font-weight-bold text-uppercase">Win PIPS</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-plus fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-danger">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $loss_pips }}+</h3>
                                <span class="font-weight-bold text-uppercase">Loss PIPS</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-caret-down fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-success">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $win_pips + $loss_pips }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total PIPS</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-flash fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-blue">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $signal }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Signal</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-bar-chart-o fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-orenge">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $category }}+</h3>
                                <span class="font-weight-bold text-uppercase">Category</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-th-large fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-pink">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $blog }}+</h3>
                                <span class="font-weight-bold text-uppercase">Blog</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-newspaper-o fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-lite-green">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $user }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total User</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-users fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-orenge">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $active_payment }}</h3>
                                <span class="font-weight-bold text-uppercase">Active Payment Method</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-credit-card text-white fa-4x float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-yellow">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $active_email }}+</h3>
                                <span class="font-weight-bold text-uppercase">Active Email Gateway</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-send-o text-white fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-blue">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $active_sms }}+</h3>
                                <span class="font-weight-bold text-uppercase">Active SMS DRIVER</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-comment-o text-white fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-pink">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $active_whatsapp }}+</h3>
                                <span class="font-weight-bold text-uppercase">Active Whatsapp Driver</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-whatsapp text-white fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-blue">
                <div class="card-content">
                    <div class="card-body text-white">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $active_telegram_user }}+</h3>
                                <span class="font-weight-bold text-uppercase">Active Telegram User</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-send fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-lite-green">
                <div class="card-content">
                    <div class="card-body text-white">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $active_whatsapp_user }}+</h3>
                                <span class="font-weight-bold text-uppercase">Active Whatsapp User</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-whatsapp fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-blue">
                <div class="card-content">
                    <div class="card-body text-white">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $total_assets }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Asset</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-th-large fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-green">
                <div class="card-content">
                    <div class="card-body text-white">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $total_symbol }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Pair</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-money fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-success">
                <div class="card-content">
                    <div class="card-body text-white">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $total_type }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Type</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-history fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-primary">
                <div class="card-content">
                    <div class="card-body text-white">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $total_frame }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Frame</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-clone fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-pink">
                <div class="card-content">
                    <div class="card-body text-white">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $total_status }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Status</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-flash fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-c-orenge">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $total_subscriber }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Subscriber</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-users fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-success">
                <div class="card-content text-white">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $total_staff }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Staff</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-user-secret fa-4x font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden-sm hidden-xs page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Statistic</h5>
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
                            <div class="col-md-4">
                                <div class="chart-container" style="position: relative; height:200px;">
                                    <h6 class="text-center">User Plan Chose</h6>
                                    {!! $userPlan->render() !!}
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="chart-container" style="position: relative; height:200px;">
                                    <h6 class="text-center">Pips Win or Loss Report</h6>
                                    {!! $pipsChart->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <h6 class="text-center">Last 30 days Signals</h6>
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
    @stack('chartJs')
@endsection
