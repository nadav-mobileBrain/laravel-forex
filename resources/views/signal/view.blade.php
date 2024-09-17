@extends('layouts.dashboard')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.fancybox.min.css') }}" />
    <script src="{{ asset('assets/admin/js/jquery.fancybox.min.js') }}"></script>
@endsection
@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Signal - {{ $signal->custom }}</h5>
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
                                                    <tr>
                                                        <td class="text-right">Selected Plan</td>
                                                        <td>
                                                            @foreach ($plans as $c)
                                                                <label class="label label-primary">{{ $c->name }}</label>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right">Selected Plan</td>
                                                        <td>
                                                            @if ($signal->post_by == 0)
                                                                <div class="badge badge-success">
                                                                    <i class="fa fa-check font-medium-2"></i>
                                                                    <span>Admin</span>
                                                                </div>
                                                            @else
                                                                <div class="badge badge-primary">
                                                                    <i class="fa fa-check font-medium-2"></i>
                                                                    <span>Staff</span>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right">Action</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-primary show_button" data-toggle="modal" data-target="#ShowModal" data-id="{{ $signal->id }}" title="Frontend"> <i class='fa fa-desktop'></i> Frontend</button>
                                                                <button type="button" class="btn btn-success result_button" data-toggle="modal" data-target="#ResultModal" data-id="{{ $signal->id }}" title="Result"> <i class='fa fa-send'></i> Result</button>
                                                                <a href="{{ route('signal-edit', $signal->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                                                                <button type="button" class="btn btn-danger delete_button" data-toggle="modal" data-target="#DelModal" data-id="{{ $signal->id }}"><i class="fa fa-trash"></i> Delete</button>
                                                            </div>
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
    </div>


    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Signal Comment And Rating</h5>
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
                                                                @if ($com->user->image != null)
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
                                                        <img src="{{ asset('assets/images/user-default.png') }}" alt="">
                                                    </div>
                                                    <div class="comment-box">
                                                        <div class="comment-head">
                                                            <h6 class="comment-name">Admin</h6>
                                                        </div>
                                                        <div class="comment-content">
                                                            <form action="{{ route('admin-comment-submit') }}" method="post">
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
                                                                @if ($com->user->image != null)
                                                                    <img src="{{ asset('assets/images') }}/{{ $com->user->image }}" alt="">
                                                                @else
                                                                    <img src="{{ asset('assets/images/user-default.png') }}" alt="">
                                                                @endif
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
                                                        <div class="comment-avatar"><img src="{{ asset('assets/images/user-default.png') }}" alt=""></div>
                                                        <div class="comment-box">
                                                            <div class="comment-head">
                                                                <h6 class="comment-name">Admin</h6>
                                                            </div>
                                                            <div class="comment-content">
                                                                <form action="{{ route('admin-rating-submit') }}" method="post">
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
                                                                    <button type="submit" class="btn btn-primary btn-lg btn-block bg-softwarezon-x "> <i class="fa fa-send"></i> Submit Comment</button>
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

    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Delete ?</strong>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('signal-delete') }}" class="form-inline">
                        {!! csrf_field() !!}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="id" class="delete_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> DELETE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ResultModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-send'></i> Result !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('signal-result') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="signal_id" class="signal_id" value="0">
                        <div class="form-group">
                            <label for="status_id" class="control-label"><b>Update Result : </b></label>
                            <select name="status_id" id="status_id" class="form-control select_status input-lg" required>
                                <option value="">Select One</option>
                                @foreach ($status as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="win" class="control-label"><b>Update Result : </b></label>
                            <select name="win" id="win" class="form-control select_status input-lg" required>
                                <option value="">Select One</option>
                                <option value="1">Win</option>
                                <option value="2">Loss</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pips" class="control-label"><b>Number of Pips: </b></label>
                            <input type="number" name="pips" id="pips" class="form-control" placeholder="Enter number of Pips" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Update Result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ShowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-desktop'></i> Home Show?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('signal-home') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="signal_id" class="signal_id" value="0">
                        <div class="form-group">
                            <label for="home" class="control-label"><b>Show Home Page: </b></label>
                            <select name="home" id="home" class="form-control select_status input-lg" required>
                                <option value="">Select One</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="win" class="control-label"><b>Show Home With Lock: </b></label>
                            <select name="home_lock" id="home_lock" class="form-control select_status input-lg" required>
                                <option value="">Select One</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on("click", '.delete_button', function(e) {
                var id = $(this).data('id');
                $(".delete_id").val(id);
            });
            $(document).on("click", '.result_button', function(e) {
                var id = $(this).data('id');
                $(".signal_id").val(id);
                var url = `/api/get-signal-result/${id}`;
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(res) {
                        $('#status_id').val(res.status_id);
                        $('#win').val(res.win);
                        $('#pips').val(res.pips);
                    }
                });
            });

            $(document).on("click", '.show_button', function(e) {
                var id = $(this).data('id');
                $(".signal_id").val(id);
                var url = `/api/get-signal-home/${id}`;
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(res) {
                        $('#home').val(res.home);
                        $('#home_lock').val(res.home_lock);
                    }
                });
            });
        });
    </script>
@endsection
