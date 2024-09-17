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

    <div class="white-bg expert-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">About Us</h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <p>{!! $basic->about !!}</p>
                    {{--<div class="intro-image wow fadeIn" data-wow-delay="0.2s">
                        <img src="{{ asset('assets/images') }}/{{ $section->about_image }}" alt="" class="img-responsive large-image">
                    </div>--}}
                </div>
            </div>
        </div>
    </div>


    <!-- expert team section start -->
    <div class="expert-section team-section gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">{{ $section->team_title }}</h2>
                        <p>{{ $section->team_description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach($team as $t)
                <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;">
                    <div class="single-team-member team-style-2 wow fadeIn" data-wow-delay="0.2s">
                        <div class="member-thumbnail">
                            <img src="{{ asset('assets/images/member') }}/{{ $t->image }}" class="img-responsive" alt="">
                        </div>
                        <div class="member-content">
                            <h4>{{ $t->name }}</h4>
                            <p>{{ $t->position }}</p>
                            <ul class="member-bookmark">
                                <li><a href="{{ $t->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{ $t->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="{{ $t->linkedin }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="{{ $t->instragram }}" target="_blank"><i class="fa fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection