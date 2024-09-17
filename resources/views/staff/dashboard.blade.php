@extends('layouts.staff')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-gradient-directional-primary">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $signal }}</h3>
                                <span class="font-weight-bold text-uppercase">Your Signal</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-bar-chart-o fa-4x white font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-gradient-directional-warning">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $win_pips }}</h3>
                                <span class="font-weight-bold text-uppercase">Win Pips</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-plus fa-4x white font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-gradient-directional-success">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $loss_pips }}</h3>
                                <span class="font-weight-bold text-uppercase">Loss Pips</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-caret-down fa-4x white font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-gradient-directional-danger">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body white text-left">
                                <h3>{{ $win_pips + $loss_pips }}+</h3>
                                <span class="font-weight-bold text-uppercase">Total Pips</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-flash fa-4x white font-large-2 float-right"></i>
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
                        <h5>Signal Statistic</h5>
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
                            <div class="col-md-12">
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <h6 class="text-center">Pips win loss by Currency Pair</h6>
                                    {!! $pipsChart->render() !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-4">
                            <div class="col-md-12">
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
@endsection
@section('scripts')
    @stack('chartJs')
@endsection
