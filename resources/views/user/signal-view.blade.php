@extends('layouts.user')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.fancybox.min.css') }}" />
    <script src="{{ asset('assets/admin/js/jquery.fancybox.min.js') }}"></script>
@endsection
@section('content')
    <div class="page-body">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">Signal : {{ $signal->custom }}</h4>
                    </div>
                    <hr>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered f-16">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" class="text-center">
                                                        Serial #{{ custom($signal->id) }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-right">Title</td>
                                                    <td>{{ $signal->title }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Asset</td>
                                                    <td>{{ $signal->asset->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Symbol</td>
                                                    <td>{{ $signal->symbol->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Type</td>
                                                    <td>{{ $signal->type->name }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-right">Status</td>
                                                    <td>{{ $signal->status->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Open Price</td>
                                                    <td>{{ $signal->entry }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Take Profit 1</td>
                                                    <td>{{ $signal->profit }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Take Profit 2</td>
                                                    <td>{{ $signal->profit_two }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Take Profit 3</td>
                                                    <td>{{ $signal->profit_three }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Stop Loss</td>
                                                    <td>{{ $signal->loss }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Time Frame</td>
                                                    <td>{{ $signal->frame->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Trade Result</td>
                                                    <td>
                                                        @if ($signal->win == null)
                                                            Pending
                                                        @else
                                                            {{ $signal->win == 1 ? '+' : '-' }}{{ $signal->pips }} Pips
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if ($signal->image)
                                        <h5>Signal Images:</h5> <br>
                                        <a data-fancybox="gallery" href="{{ asset("assets/images/signal/$signal->image") }}">
                                            <img src="{{ asset("assets/images/signal/$signal->image") }}" style="width: 30%" alt="">
                                        </a>
                                    @endif
                                    <hr>
                                    <h5 class="card-title">Signal Description : </h5> <br>
                                    {!! $signal->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">Comments And Rating</h4>
                    </div>
                    <hr>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active text-uppercase" id="active-tab" data-toggle="tab" href="#active" aria-controls="active" aria-expanded="true">
                                        <h4>Signal Comments</h4>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-uppercase" id="link-tab" data-toggle="tab" href="#link" aria-controls="link" aria-expanded="false">
                                        <h4>Signal Ratings</h4>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content px-1 pt-1">
                                <div role="tabpanel" class="tab-pane in active" id="active" aria-labelledby="active-tab" aria-expanded="true">

                                    <div class="comments-container">
                                        <h3>Comments ({{ $total_comment }})</h3>

                                        <ul id="comments-list" class="comments-list">
                                            @foreach ($comments as $com)
                                                @if ($com->user_id == 0)
                                                    <li>
                                                        <div class="comment-main-level">
                                                            <div class="comment-avatar">
                                                                <img src="{{ asset('assets/images/user-default.png') }}" alt="">
                                                            </div>
                                                            <div class="comment-box">
                                                                <div class="comment-head">
                                                                    <h6 class="comment-name">Admin</h6>
                                                                    <span style="margin-top: -4px;">{{ \Carbon\Carbon::parse($com->created_at)->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="comment-content">
                                                                    {{ $com->comment }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @elseif($com->user_id == -1)
                                                    <li>
                                                        <div class="comment-main-level">
                                                            <div class="comment-avatar">
                                                                <img src="{{ asset('assets/images/user-default.png') }}" alt="">
                                                            </div>
                                                            <div class="comment-box">
                                                                <div class="comment-head">
                                                                    <h6 class="comment-name">Staff</h6>
                                                                    <span style="margin-top: -4px;">{{ \Carbon\Carbon::parse($com->created_at)->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="comment-content">
                                                                    {{ $com->comment }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li>
                                                        <div class="comment-main-level">
                                                            <div class="comment-avatar">
                                                                @if (Auth::user()->image != null)
                                                                    <img src="{{ asset('assets/images') }}/{{ $com->user->image }}" alt="">
                                                                @else
                                                                    <img src="{{ asset('assets/images/user-default.png') }}" alt="">
                                                                @endif
                                                            </div>
                                                            <div class="comment-box">
                                                                <div class="comment-head">
                                                                    <h6 class="comment-name">{{ $com->user->name }}</h6>
                                                                    <span style="margin-top: -4px;">{{ \Carbon\Carbon::parse($com->created_at)->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="comment-content">
                                                                    {{ $com->comment }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @endforeach
                                            <li>
                                                <div class="comment-main-level">
                                                    <!-- Avatar -->
                                                    <div class="comment-avatar">
                                                        @if (Auth::user()->image != null)
                                                            <img src="{{ asset('assets/images') }}/{{ Auth::user()->image }}" alt="">
                                                        @else
                                                            <img src="{{ asset('assets/images/user-default.png') }}" alt="">
                                                        @endif
                                                    </div>
                                                    <div class="comment-box">
                                                        <div class="comment-head">
                                                            <h6 class="comment-name">{{ Auth::user()->name }} - {{ Auth::user()->plan->name }}</h6>
                                                        </div>
                                                        <div class="comment-content">
                                                            <form action="{{ route('comment-submit') }}" method="post">
                                                                {!! csrf_field() !!}
                                                                <input type="hidden" name="signal_id" value="{{ $signal->id }}">
                                                                <textarea name="comment" id="" required cols="30" rows="3" placeholder="Write Your Comment here." class="form-control"></textarea>
                                                                <br>
                                                                <button type="submit" class="btn btn-primary btn-lg btn-block bg-softwarezon-x "> <i class="fa fa-send"></i> Submit Comment</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                                <div class="tab-pane" id="link" role="tabpanel" aria-labelledby="link-tab" aria-expanded="false">
                                    <div class="comments-container">
                                        <h3>Rating - {!! \App\TraitsFolder\CommonTrait::getRating($final_rating) !!} ({{ $total_rating }})</h3>

                                        <ul id="comments-list" class="comments-list">
                                            @foreach ($rating as $com)
                                                @if ($com->user_id == 0)
                                                    <li>
                                                        <div class="comment-main-level">
                                                            <div class="comment-avatar">
                                                                <img src="{{ asset('assets/images/user-default.png') }}" alt="">
                                                            </div>
                                                            <div class="comment-box">
                                                                <div class="comment-head">
                                                                    <h6 class="comment-name">Admin - {!! \App\TraitsFolder\CommonTrait::getRating($com->rating) !!} ({{ $com->rating }})</h6>
                                                                    <span style="margin-top: -4px;">{{ \Carbon\Carbon::parse($com->created_at)->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="comment-content">
                                                                    {{ $com->comment }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @elseif($com->user_id == -1)
                                                    <li>
                                                        <div class="comment-main-level">
                                                            <div class="comment-avatar">
                                                                <img src="{{ asset('assets/images/user-default.png') }}" alt="">
                                                            </div>
                                                            <div class="comment-box">
                                                                <div class="comment-head">
                                                                    <h6 class="comment-name">Staff - {!! \App\TraitsFolder\CommonTrait::getRating($com->rating) !!} ({{ $com->rating }})</h6>
                                                                    <span style="margin-top: -4px;">{{ \Carbon\Carbon::parse($com->created_at)->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="comment-content">
                                                                    {{ $com->comment }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li>
                                                        <div class="comment-main-level">
                                                            <div class="comment-avatar">
                                                                <img src="{{ asset('assets/images') }}/{{ $com->user->image }}" alt="">
                                                            </div>
                                                            <div class="comment-box">
                                                                <div class="comment-head">
                                                                    <h6 class="comment-name">{{ $com->user->name }} - {!! \App\TraitsFolder\CommonTrait::getRating($com->rating) !!} ({{ $com->rating }})</h6>
                                                                    <span style="margin-top: -4px;">{{ \Carbon\Carbon::parse($com->created_at)->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="comment-content">
                                                                    {{ $com->comment }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @endforeach
                                            @if ($user_rating == null)
                                                <li>
                                                    <div class="comment-main-level">
                                                        <!-- Avatar -->
                                                        <div class="comment-avatar">
                                                            <img src="{{ asset('assets/images') }}/{{ Auth::user()->image }}" alt="">
                                                        </div>
                                                        <div class="comment-box">
                                                            <div class="comment-head">
                                                                <h6 class="comment-name">{{ Auth::user()->name }} - {{ Auth::user()->plan->name }}</h6>
                                                            </div>
                                                            <div class="comment-content">
                                                                <form action="{{ route('rating-submit') }}" method="post">
                                                                    {!! csrf_field() !!}
                                                                    <input type="hidden" name="signal_id" value="{{ $signal->id }}">
                                                                    <div class="listing_rating" style="margin-top:10px;">
                                                                        <input name="rating" id="rating-1" value="5" type="radio" required="">
                                                                        <label for="rating-1" class="fa fa-star"></label>
                                                                        <input name="rating" id="rating-2" value="4" type="radio" required="">
                                                                        <label for="rating-2" class="fa fa-star"></label>
                                                                        <input name="rating" id="rating-3" value="3" type="radio" required="">
                                                                        <label for="rating-3" class="fa fa-star"></label>
                                                                        <input name="rating" id="rating-4" value="2" type="radio" required="">
                                                                        <label for="rating-4" class="fa fa-star"></label>
                                                                        <input name="rating" id="rating-5" value="1" type="radio" required="">
                                                                        <label for="rating-5" class="fa fa-star"></label>
                                                                    </div>
                                                                    <textarea name="comment" id="" required cols="30" rows="3" placeholder="Write Your Comment here." class="form-control"></textarea>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-primary btn-lg btn-block bg-softwarezon-x "> <i class="fa fa-send"></i> Submit Rating</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
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
