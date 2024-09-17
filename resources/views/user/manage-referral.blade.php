@extends('layouts.user')
@section('style')
    <style>

        ::-moz-focus-inner {
            padding: 0;
            border: 0;
        }
        button {
            position: relative;
            /*  background-color: #aaa;
              border-radius: 0 3px 3px 0;
              cursor: pointer;*/
        }
        .copied::after {
            position: absolute;
            top: 12%;
            right: 110%;
            display: block;
            content: "COPIED";
            font-size: 1.40em;
            padding: 2px 10px;
            color: #fff;
            background-color: #22a;
            border-radius: 3px;
            opacity: 0;
            will-change: opacity, transform;
            animation: showcopied 1.5s ease;
        }
        @keyframes showcopied {
            0% {
                opacity: 0;
                transform: translateX(100%);
            }
            70% {
                opacity: 1;
                transform: translateX(0);
            }
            100% {
                opacity: 0;
            }
        }
    </style>
@stop
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
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block">
                                        <h5 class="text-left">Your Referral URL : </h5>
                                        <br>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <div class="input-group mb15">
                                                    <input type="text" class="form-control input-lg" name="coin_url" id="ref" readonly value="{{ route('register-ref',Auth::user()->username) }}"/>
                                                    <span class="input-group-btn">
                                                        <button type="button" data-copytarget="#ref" class="btn btn-success btn-lg"><i class="fa fa-clipboard"></i> CLIPBOARD</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                <tr>
                                    <th>ID#</th>
                                    <th>Register Date</th>
                                    <th>User Details</th>
                                    <th>Current Plan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user as $k => $p)
                                    <tr>
                                        <td>{{ ++$k }}</td>
                                        <td>{{\Carbon\Carbon::parse($p->created_at)->format('dS M, Y - h:i:s A')}}</td>
                                        <td>{{ $p->referralUser->name }}</td>
                                        <td>{{ $p->referralUser->plan->name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$user->links('basic.pagination')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function() {
            'use strict';
            // click events
            document.body.addEventListener('click', copy, true);
            // event handler
            function copy(e) {
                // find target element
                var
                    t = e.target,
                    c = t.dataset.copytarget,
                    inp = (c ? document.querySelector(c) : null);

                // is element selectable?
                if (inp && inp.select) {

                    // select text
                    inp.select();

                    try {
                        // copy text
                        document.execCommand('copy');
                        inp.blur();

                        // copied animation
                        t.classList.add('copied');
                        setTimeout(function() { t.classList.remove('copied'); }, 1500);
                    }
                    catch (err) {
                        alert('please press Ctrl/Cmd+C to copy');
                    }
                }
            }

        })();
    </script>
@stop
