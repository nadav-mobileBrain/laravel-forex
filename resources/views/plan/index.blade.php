@extends('layouts.dashboard')
@section('style')
    <link rel="stylesheet" href="{{ asset('public/assets/admin/css/pricing.css') }}">
@endsection
@section('content')
    <div class="page-body">

        <div class="row no-gutters">
            @foreach ($plan as $k => $p)
                <div class="col-md-4 pr-1">
                    <div class="list-group text-center my-3">
                        <div class="list-group-item text-white bg-dark">
                            <h4 class="text-center text-uppercase font-weight-bold my-1">{{ $p->name }}</h4>
                        </div>
                        <div class="list-group-item text-uppercase font-weight-bold">
                            <h3>
                                @if ($p->price_type == 0)
                                    FREE
                                @else
                                    {{ $basic->symbol }}{{ $p->price }}
                                @endif
                            </h3>
                        </div>
                        @if ($p->plan_type == 0)
                            <a href="#" class="list-group-item">
                                <h4>{{ $p->duration }} - Days</h4>
                            </a>
                        @else
                            <a href="#" class="list-group-item">
                                <h4>Unlimited</h4>
                            </a>
                        @endif
                        <a href="#" class="list-group-item">
                            <h4>Dashboard Signal - {{ $p->dashboard_status == 1 ? 'YES' : 'NO' }}</h4>
                        </a>
                        <a href="#" class="list-group-item">
                            <h4>Whatsapp Alert - {{ $p->whatsapp_status == 1 ? 'YES' : 'NO' }}</h4>
                        </a>
                        <a href="#" class="list-group-item">
                            <h4>Telegram Alert - {{ $p->telegram_status == 1 ? 'YES' : 'NO' }}</h4>
                        </a>
                        <a href="#" class="list-group-item">
                            <h4>Email Alert - {{ $p->email_status == 1 ? 'YES' : 'NO' }}</h4>
                        </a>
                        <a href="#" class="list-group-item">
                            <h4>SMS Alert - {{ $p->sms_status == 1 ? 'YES' : 'NO' }}</h4>
                        </a>
                        <a href="#" class="list-group-item">
                            <h4>Phone Consulting - {{ $p->call_status == 1 ? 'YES' : 'NO' }}</h4>
                        </a>
                        @if ($p->contents)
                            @foreach ($p->contents as $content)
                                <a href="#" class="list-group-item">
                                    <h4>{{ $content }}</h4>
                                </a>
                            @endforeach
                        @endif

                        <a href="#" class="list-group-item">
                            <h4>Support - {{ $p->support }}</h4>
                        </a>
                        <a href="#" class="list-group-item">
                            <h4>{{ $p->status == 1 ? 'ACTIVATE' : 'DEACTIVATE' }}</h4>
                        </a>
                        <div class="list-group-item">
                            <a href="{{ route('plan-edit', $p->id) }}" class="btn btn-secondary btn-lg btn-block text-truncate"><i class="fa fa-edit"></i> EDIT PLAN</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
