@extends('layouts.user')
@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $page_title }}</h5>
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

                        @if ($lock)
                            <div class="alert alert-warning bg-warning text-white" role="alert">
                                <h4 class="alert-heading"><i class="ti ti-bookmark"></i> Warning!</h4>
                                <h5 class="mt-3">You are seeing this lock image due to your plan duration may expire or your plan payment not be completed.</h5>
                                <h5 class="mt-3 mb-0">To remove this lock you need to complete the payment.
                                    <a href="{{ route('chose-payment-method') }}" class="btn btn-mini btn-primary font-weight-bold"><i class="ti ti-credit-card"></i>Click to Pay</a>
                                    <a href="{{ route('user-dashboard') }}" class="btn btn-mini btn-danger font-weight-bold"><i class="ti ti-layout-media-overlay"></i>Go to Dashboard</a>
                                </h5>
                            </div>
                        @endif

                        @foreach ($signal->chunk(4) as $signals)
                            <div class="row">
                                @foreach ($signals as $k => $p)
                                    <div class="col-md-3">
                                        <div class="card">
                                            <img class="card-img-top" src="{{ asset("assets/images/signal/$p->image") }}" alt="Card image cap">
                                            <div class="card-header d-flex justify-content-between bd-highlight">
                                                <h5 class="card-title">{{ $p->symbol->name }}</h5>
                                                <h5 class="card-title">{{ $p->type->name }}</h5>
                                            </div>
                                            <ul class="list-group list-group-flush font-weight-bold">
                                                <li class="list-group-item d-flex justify-content-between list-group-item-primary">
                                                    <div>SL</div>
                                                    <div>{{ custom($p->id) }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-info">
                                                    <div>Asset</div>
                                                    <div>{{ $p->asset->name }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-info">
                                                    <div>Status</div>
                                                    <div>{{ $p->status->name }}</div>
                                                </li>
                                                @if ($lock)
                                                    <li class="list-group-item">
                                                        <div class="text-center">
                                                            <img src="{{ asset('assets/images/lock.png') }}" alt="">
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="list-group-item d-flex justify-content-between list-group-item-success">
                                                        <div>Open Price</div>
                                                        <div>{{ $p->entry }}</div>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between list-group-item-secondary">
                                                        <div>Take Profit 1</div>
                                                        <div>{{ $p->profit }}</div>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between list-group-item-secondary">
                                                        <div>Take Profit 2</div>
                                                        <div>{{ $p->profit_two }}</div>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between list-group-item-secondary">
                                                        <div>Take Profit 3</div>
                                                        <div>{{ $p->profit_three }}</div>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between list-group-item-warning">
                                                        <div>Stop Loss</div>
                                                        <div>{{ $p->loss }}</div>
                                                    </li>
                                                @endif
                                                <li class="list-group-item d-flex justify-content-between list-group-item-info">
                                                    <div>Time Frame</div>
                                                    <div>{{ $p->frame->name }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-info">
                                                    <div>Trade Result</div>
                                                    <div>
                                                        @if ($p->win == null)
                                                            Pending
                                                        @else
                                                            {{ $p->win == 1 ? '+' : '-' }}{{ $p->pips }} Pips
                                                        @endif
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <div>Rating</div>
                                                    <div>
                                                        @php
                                                            if ($p->ratings_count == 0) {
                                                                $final_rating = 0;
                                                            } else {
                                                                $final_rating = round($p->ratings_sum_rating / $p->ratings_count);
                                                            }
                                                        @endphp
                                                        {!! \App\TraitsFolder\CommonTrait::getRating($final_rating) !!} ({{ $p->ratings_count }})
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <div>Action</div>
                                                    <div>
                                                        <a href="{{ route('user-signal-view', $p->custom) }}" class="btn btn-primary btn-mini" title="View"><i class="fa fa-eye"></i> Details</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        {{ $signal->links('basic.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
