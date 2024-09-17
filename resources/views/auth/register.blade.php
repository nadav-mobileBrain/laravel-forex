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
                        <h2 class="area-title">Register Here</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-12">
                    <form class="m-t-20" action="{{ route('register') }}" autocomplete="off" method="post" data-aos="fade-up" data-aos-duration="1200">
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
                                    <label for="referral">Referral Id: <code>(Optional)</code></label>
                                    <input class="form-control" value="{{ $referral }}" name="referral" id="referral" type="text" placeholder="Referral ID (Optional)" autocomplete="false" {{ $referral ? 'readonly' : '' }}>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="referral">Full Name:</label>
                                    <input class="form-control" value="{{ old('name') }}" name="name" type="text" placeholder="Full Name" autocomplete="false" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="referral">Username:</label>
                                    <input class="form-control" value="{{ old('username') }}" name="username" type="text" placeholder="User Name" autocomplete="false" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="referral">Email Address:</label>
                                    <input class="form-control" value="{{ old('email') }}" name="email" type="email" placeholder="Email address" required>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label for="referral">Country Code:</label>
                                    <select name="country_code" id="country_code" class="form-control input-lg" required>
                                        @foreach ($country as $cn)
                                            <option value="{{ $cn['dial_code'] }}" {{ old('country_code') == $cn['dial_code'] ? 'selected' : '' }}>{{ $cn['name'] }} ({{ $cn['dial_code'] }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <label for="referral">Phone Number:</label>
                                    <input class="form-control" value="{{ old('phone') }}" name="phone" type="text" placeholder="Phone Number" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="referral">Select Plan:</label>
                                    <select name="plan_id" id="plan_id" class="form-control input-lg" required data-error="Select One Subscription Plan" style="line-height: 30px!important;">
                                        <option value="">Select Plan</option>
                                        @foreach ($plan as $p)
                                            <option {{ old('plan_id') == $p->id ? 'selected' : '' }} value="{{ $p->id }}">{{ $p->name }} -
                                                @if ($p->price_type == 0)
                                                    FREE
                                                @else
                                                    {{ $p->price }} {{ $basic->currency }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="referral">Password:</label>
                                    <input class="form-control" name="password" type="password" placeholder="Password" autocomplete="rrr" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="referral">Password Confirmation:</label>
                                    <input class="form-control" name="password_confirmation" type="password" placeholder="Confirm password" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                                        <input type="checkbox" class="custom-control-input" required>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">I agree to <a href="{{ route('terms-condition') }}" target="_blank" class="link">terms and conditions</a> and <a href="{{ route('privacy-policy') }}" target="_blank" class="link">privacy and policy</a></span>
                                    </label>
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
                                <button type="submit" class="btn btn-lg btn-block btn-primary btn-arrow"><span> Create Account <i class="ti-arrow-right"></i></span></button>
                                <!--  -->
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12 text-center m-t-30">
                            <div class="have-ac ml-auto align-self-center">Already have an account? <a href="{{ route('login') }}" class="text-danger">Login</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
@section('scripts')
@endsection
