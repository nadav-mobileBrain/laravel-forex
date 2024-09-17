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
                <div class="col-md-8 col-md-offset-2 col-sm-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">Verify Email Address</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-12">

                    <p class="text-center">
                        Please check your email inbox or spam/junk box,it maybe takes a few minutes to receive it and copy the verification code in box below.
                    </p>

                    <form class="m-t-20" action="{{ route('verification-submit') }}" autocomplete="off" method="post" data-aos="fade-left" data-aos-duration="1200">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-lg-12">
                                @if (session()->has('message'))
                                    <div class="alert alert-danger alert-dismissable">
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
                                    <label for="">Verification Code</label>
                                    <input class="form-control" name="code" type="text" placeholder="Verification Code" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-lg btn-block btn-primary btn-arrow"><span> Verify Now <i class="ti-arrow-right"></i></span></button>
                                <!--  -->
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12 text-center m-t-30">
                            <div class="have-ac ml-auto align-self-center">
                                <form action="{{ route('email-resubmit') }}" method="post">
                                    {!! csrf_field() !!}
                                    <p>Not receive Verification Code. <button type="submit" class="btn btn-xs btn-primary">Resent Email</button></p>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
