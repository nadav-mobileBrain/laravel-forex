@extends('layouts.frontEnd')
@section('slider')
    <div class="slider-area clearfix">
        <div id="expert-slider">
            @foreach ($slider as $s)
                <div class="expert-single-slide" style="background-image: url('{{ asset('assets/images/slider') }}/{{ $s->image }}');">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="slide-content-wrapper">
                                    <div class="slide-content text-left">
                                        <h3 class="slide-subtitle colored-text" data-animation="fadeInLeft" data-delay="0.5s">{{ $s->main_title }}</h3>
                                        <h2 class="slide-title colored-text" data-animation="fadeInUp" data-delay="1s">{{ $s->sub_title }}</h2>
                                        <p class="slide-description colored-text" data-animation="fadeInDown" data-delay="1.5s">{{ $s->slider_text }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('content')

    <div class="expert-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">{{ $section->about_title }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="intro-image wow fadeIn" data-wow-delay="0.2s">
                        <img src="{{ asset('assets/images') }}/{{ $section->about_image }}" alt="" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class=" wow fadeIn" data-wow-delay="0.2s">
                        <div class="intro-description">
                            <p class="text-justify">{!! $section->about_description !!}</p>
                        </div>
                        <div style="text-align: center;">
                            <a class="button btn-block" href="{{ route('register') }}"><i class="fa fa-sign-in"></i> Register Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="feature-section gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">{{ $section->speciality_title }}</h2>
                        <p>{{ $section->speciality_description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach ($speciality as $key => $sp)
                    <div class="col-md-4 col-sm-6">
                        <div class="single-feature wow fadeIn" data-wow-delay="0.2s">
                            <div class="extraSpeciality">{!! $sp->icon !!}</div>
                            <div class="feature-content">
                                <h4>{{ $sp->name }}</h4>
                                <p>{!! $sp->description !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="expert-section colored-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center title-white ">
                        <h2 class="area-title">{{ $section->currency_title }}</h2>
                        <p>{{ $section->currency_description }}</p>
                    </div>
                </div>
            </div>
            @foreach ($signals->chunk(4) as $signal)
                <div class="row">
                    @foreach ($signal as $p)
                        <div class="col-md-3">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <strong class="">{{ $p->symbol->name }}</strong>
                                    </div>
                                    <div class="pull-right">
                                        <strong class="text-right">{{ $p->type->name }}</strong>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="pull-left">
                                            <strong class="">Asset</strong>
                                        </div>
                                        <div class="pull-right">
                                            <strong class="text-right">{{ $p->asset->name }}</strong>
                                        </div>
                                        <div class="clearfix"></div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="pull-left">
                                            <strong class="">Status</strong>
                                        </div>
                                        <div class="pull-right">
                                            <strong class="text-right">{{ $p->status->name }}</strong>
                                        </div>
                                        <div class="clearfix"></div>
                                    </li>
                                    @if ($p->home_lock)
                                        <li class="list-group-item">
                                            <div class="text-center">
                                                <img src="{{ asset('assets/images/lock.png') }}" alt="">
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                    @else
                                        <li class="list-group-item list-group-item-success">
                                            <div class="pull-left">
                                                <strong class="">Open Price</strong>
                                            </div>
                                            <div class="pull-right">
                                                <strong class="text-right">{{ $p->entry }}</strong>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-left">
                                                <strong class="">TP 1</strong>
                                            </div>
                                            <div class="pull-right">
                                                <strong class="text-right">{{ $p->profit }}</strong>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-left">
                                                <strong class="">TP 2</strong>
                                            </div>
                                            <div class="pull-right">
                                                <strong class="text-right">{{ $p->profit_two ?? '-' }}</strong>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-left">
                                                <strong class="">TP 3</strong>
                                            </div>
                                            <div class="pull-right">
                                                <strong class="text-right">{{ $p->profit_three ?? '-' }}</strong>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                        <li class="list-group-item list-group-item-warning">
                                            <div class="pull-left">
                                                <strong class="">Stop Loss</strong>
                                            </div>
                                            <div class="pull-right">
                                                <strong class="text-right">{{ $p->loss }}</strong>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                    @endif
                                    <li class="list-group-item">
                                        <div class="pull-left">
                                            <strong class="">Time Frame</strong>
                                        </div>
                                        <div class="pull-right">
                                            <strong class="text-right">{{ $p->frame->name }}</strong>
                                        </div>
                                        <div class="clearfix"></div>
                                    </li>
                                    <li class="list-group-item list-group-item-success">
                                        <div class="pull-left">
                                            <strong class="">Result</strong>
                                        </div>
                                        <div class="pull-right">
                                            <strong class="text-right">
                                                @if ($p->win == null)
                                                    Pending
                                                @else
                                                    {{ $p->win == 1 ? '+' : '-' }}{{ $p->pips }} Pips
                                                @endif
                                            </strong>
                                        </div>
                                        <div class="clearfix"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="expert-section gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">{{ $section->price_title }}</h2>
                        <p>{{ $section->price_description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach ($plan as $pl)
                    <div class="col-lg-4 col-md-4 col-sm-6" style="margin-top: 20px;">
                        <div class="single-price-table table-active wow fadeIn" data-wow-delay="0.6s">
                            <div class="pricing-head">
                                <h4 class="pricing-title">{{ $pl->name }}</h4>
                            </div>
                            <div class="pricing-content">
                                <div class="pricing-value-wrapper">
                                    @if ($pl->price_type == 0)
                                        <h2 class="pricing-value">
                                            FREE
                                            @if ($pl->plan_type == 0)
                                                <sub>/ {{ $pl->duration }} days</sub>
                                            @else
                                                <sub>/ unlimited</sub>
                                            @endif
                                        </h2>
                                    @else
                                        <h2 class="pricing-value">
                                            <sup>{{ $basic->currency }}</sup>
                                            {{ $pl->price }}
                                            @if ($pl->plan_type == 0)
                                                <sub>/ {{ $pl->duration }} days</sub>
                                            @else
                                                <sub>/ unlimited</sub>
                                            @endif
                                        </h2>
                                    @endif
                                </div>
                                <ul class="table-content">
                                    <li>Dashboard Signal - {{ $pl->dashboard_status == 1 ? 'YES' : 'NO' }}</li>
                                    <li>Whatsapp Alert - {{ $pl->whatsapp_status == 1 ? 'YES' : 'NO' }}</li>
                                    <li>Telegram Alert - {{ $pl->telegram_status == 1 ? 'YES' : 'NO' }}</li>
                                    <li>Email Alert - {{ $pl->email_status == 1 ? 'YES' : 'NO' }}</li>
                                    <li>SMS Alert - {{ $pl->sms_status == 1 ? 'YES' : 'NO' }}</li>
                                    <li>Phone Consulting - {{ $pl->call_status == 1 ? 'YES' : 'NO' }}</li>
                                    @if ($pl->contents)
                                        @foreach ($pl->contents as $content)
                                            <li>{{ $content }}</li>
                                        @endforeach
                                    @endif
                                    <li>Support - {{ $pl->support }}</li>
                                </ul>
                            </div>
                            <div class="pricibg-footer">
                                <a href="{{ route('register') }}" class="button button-small">Subscribe now</a>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>

    <div class="expert-section" style="background: url('{{ asset('assets/images') }}/{{ $section->counter_image }}')">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="single-counter wow fadeIn" data-wow-delay="0.2s">
                        <div class="counter-icon">
                            <i class="bi bi-spark"></i>
                        </div>
                        <div class="counter-text">
                            <p class="fact-number">{{ $total_signal }}</p>
                            <h4>Total Signal</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-counter wow fadeIn" data-wow-delay="0.3s">
                        <div class="counter-icon">
                            <i class="bi bi-link"></i>
                        </div>
                        <div class="counter-text">
                            <p class="fact-number">{{ $pips_sum }}</p>
                            <h4>Total Pips</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-counter wow fadeIn" data-wow-delay="0.4s">
                        <div class="counter-icon">
                            <i class="bi bi-article"></i>
                        </div>
                        <div class="counter-text">
                            <p class="fact-number">{{ $total_blog }}</p>
                            <h4>Total Blog</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-counter wow fadeIn" data-wow-delay="0.5s">
                        <div class="counter-icon">
                            <i class="bi bi-group"></i>
                        </div>
                        <div class="counter-text">
                            <p class="fact-number">{{ $total_user }}</p>
                            <h4>Happy User</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="expert-section gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">{{ $section->trading_title }}</h2>
                        <p>{{ $section->trading_description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    {!! $section->trading_script !!}

                </div>
            </div>
        </div>
    </div>

    <div class="colored-bg call-to-action-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="action-content">
                        <div class="action-heading">
                            <h3>{{ $section->advertise_title }}</h3>
                            <p>{{ $section->advertise_description }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="action-button">
                        <a href="{{ route('register') }}" class="btn button btn-primary btn-white" style="background: #ffffff;"><i class="fa fa-send"></i> Register Now.!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="expert-section testimonial-section gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">{{ $section->testimonial_title }}</h2>
                        <p>{{ $section->testimonial_description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="testimonial-wrapper navigation-one text-center">
                        @foreach ($testimonial as $key => $t)
                            <div class="single-testimonial">
                                <blockquote>
                                    <img src="{{ asset('assets/images/testimonial') }}/{{ $t->image }}" alt="{{ $t->name }}" class="client-image">
                                    <p>{{ $t->message }}</p>
                                    <p class="client-name">{{ $t->name }} <span class="designation">{{ $t->position }}</span></p>
                                </blockquote>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="subscribe-section colored-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="subscribe-content">
                        <h3>{{ $section->subscriber_title }}</h3>
                        <p>{{ $section->subscriber_description }}</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="subscription-box">
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
                        <form class="subscription-form" method="POST" action="{{ route('submit-subscribe') }}">
                            {!! csrf_field() !!}
                            <div class="subscribe-input">
                                <input type="email" class="subscribe-control" required name="email" placeholder="Enter Your Email">
                            </div>
                            <div class="subscribe-input">
                                <button class="button email-submit-btn btn-white" type="submit" style="background: #ffffff;"><i class="fa fa-paper-plane"></i> Subscribe Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="expert-section blog-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="area-heading text-center">
                        <h2 class="area-title">{{ $section->blog_title }}</h2>
                        <p>{{ $section->blog_description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($blog as $b)
                    <div class="col-md-4 col-sm-6" style="margin-bottom: 20px;">
                        <article class="blog-post">
                            <div class="post-thumbnail">
                                <a href="#"><img src="{{ asset('assets/images/post') }}/{{ $b->image }}" alt=""></a>
                            </div>
                            <div class="post-content">
                                <h5 class="post-title"><a href="{{ route('blog-details', $b->slug) }}">{{ substr($b->title, 0, 30) }}{{ strlen($b->title) > 33 ? '...' : '' }}</a></h5>
                                <ul class="post-date list-inline">
                                    <li><a href="#"><i class="fa fa-calendar"></i>{{ \Carbon\Carbon::parse($b->created_at)->format('dS M, Y') }}</a></li>
                                    <li><a href="#"><i class="fa fa-flag"></i>{{ $b->category->name }}</a></li>
                                </ul>
                                <p>{{ substr(strip_tags($b->description), 0, 120) }}..</p>
                                <a class="button" href="{{ route('blog-details', $b->slug) }}">Read More</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
