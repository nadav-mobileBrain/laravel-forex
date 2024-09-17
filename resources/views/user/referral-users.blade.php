@extends('layouts.user')
@section('style')
    <link href="{{ asset('assets/admin/css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endsection
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
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>YOUR REFERRAL LINK:</strong></label>
                                <div class="input-group mb15">
                                    <input type="text" class="form-control input-lg" id="ref" readonly value="{{ route('auth.reference-register', $user->username) }}" />
                                    <span class="input-group-btn">
                                        <button data-clipboard-target="#ref" class="btn btn-success btn-lg clip">COPY</button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label><strong>REFERRAL ID:</strong></label>
                                <div class="input-group mb15">
                                    <input type="text" class="form-control input-lg" id="ref1" readonly value="{{ $user->username }}" />
                                    <span class="input-group-btn">
                                        <button data-clipboard-target="#ref1" class="btn btn-success btn-lg clip">COPY</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Plan</th>
                                                <th>Payment</th>
                                                <th>Register At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $key => $ref)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>
                                                        {{ $ref->name }} <br>
                                                        {{ $ref->email }}
                                                    </td>
                                                    <td>
                                                        {{ $ref->username }} <br>
                                                        {{ $ref->country_code }}{{ $ref->phone }}
                                                    </td>
                                                    <td>{{ $ref->plan->name }}</td>
                                                    <td>
                                                        @if ($ref->plan_status)
                                                            <span class="badge badge-success">Complete</span>
                                                        @else
                                                            <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $ref->created_at->toDateString() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
    <script>
        new ClipboardJS('.btn');
    </script>
@endsection
