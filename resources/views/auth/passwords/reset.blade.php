@extends('layouts.frontEnd')

@section('content')

    <div class="expert-section gray-bg breadcrumb-area" style="background: url('{{ asset('assets/images') }}/{{ $basic->breadcrumb }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h3 class="breadcrumb-title">{{ $page_title }}</h3>
                    <div class="breadcrumb-wrap">
                        <ul class="breadcrumb-list">
                            <li><a href="{{ route('home') }}">Home </a></li>
                            <li><a href="#">{{ $page_title }} </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="expert-section gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">Reset Your Password</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-12">

                    <form class="m-t-20" action="{{ route('password.request') }}" autocomplete="off" method="post" data-aos="fade-left" data-aos-duration="1200">
                        {!! csrf_field() !!}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="row">
                            <div class="col-lg-12">
                                @if (session()->has('message'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        {{ session()->get('message') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            {!! $error !!}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Email Address</label>
                                    <input class="form-control" name="email" type="email" placeholder="Email address" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input class="form-control" name="password" type="password" placeholder="Password" autocomplete="rrr" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Password Confirmation</label>
                                    <input class="form-control" name="password_confirmation" type="password" placeholder="Confirm password" required>
                                </div>
                            </div>
                            @if (env('CAPTCHA_STATUS'))
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! Captcha::display() !!}
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-12" style="margin-top: 20px;">
                                <button type="submit" class="btn btn-lg btn-block btn-primary btn-arrow"><span> Reset Password <i class="ti-arrow-right"></i></span></button>
                                <!--  -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
