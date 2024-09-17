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
                        <h2 class="area-title">Login</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-12">

                    <form class="m-t-20" action="{{ route('login') }}" autocomplete="off" method="post" data-aos="fade-up" data-aos-duration="1200">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-lg-12">
                                @if (session()->has('message'))
                                    <div class="alert alert-warning alert-dismissable">
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
                                    <label for="">Email Address:</label>
                                    <input class="form-control" name="email" value="{{ old('email') }}" type="email" placeholder="Email address" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Password:</label>
                                    <input class="form-control" name="password" type="password" placeholder="Password" autocomplete="rrr" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                                        <input type="checkbox" class="custom-control-input" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Remember Me</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group text-right">
                                    <a href="{{ route('password.request') }}" class="card-link">Forgot Password?</a>
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
                                <button type="submit" class="btn btn-block btn-lg btn-primary"><span> Login Now <i class="ti-arrow-right"></i></span></button>
                                <!--  -->
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12 text-center m-t-30">
                            <div class="have-ac ml-auto align-self-center">Create an account? <a href="{{ route('register') }}" class="text-danger">Register</a></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


@endsection
