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

                            <div class="col-md-12">

                                <div class="table-scrollable bg-info">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th> #Step </th>
                                                <th> DESCRIPTION </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td><b>#Step 1</b> </td>
                                                <td><b> Install Telegram Android, IOS or Desktop App.</b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 2</b> </td>
                                                <td><b> Enter Your Whatsapp number with Country code except (+).</b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 3</b> </td>
                                                <td><b> Press Send Active Whatsapp Token Button.</b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 4</b> </td>
                                                <td><b> On your entered whatsapp number we will reply a token.</b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 5</b> </td>
                                                <td><b> Enter the token on your Token box and press Active Whatsapp Button.</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if ($user->plan->whatsapp_status && $user->plan_status)
                                <div class="col-md-12">
                                    @if ($user->whatsapp_id == null)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Send Whatsapp Number</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <form action="{{ route('submit-user-whatsapp-number') }}" method="post">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="whatsapp_number">Whatsapp Number: <code>With Country code but except (+)</code></label>
                                                                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}" placeholder="Whatsapp Number" class="form-control" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary btn-lg btn-block">Send Active Whatsapp Token</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Send Whatsapp Activation Token</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <form action="{{ route('submit-user-whatsapp-token') }}" method="post">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="whatsapp_token">Whatsapp Token: </label>
                                                                <input type="text" name="whatsapp_token" id="whatsapp_token" value="{{ old('whatsapp_token') }}" placeholder="Whatsapp Token" class="form-control" required>
                                                            </div>
                                                            <input type="hidden" name="whatsapp_number" id="whatsapp_number" value="{{ session()->get('wa_number') }}" placeholder="Whatsapp Number" class="form-control" required>

                                                            <button type="submit" class="btn btn-primary btn-lg btn-block">Submit Activation Token</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-primary text-center background-primary" role="alert">
                                            <strong>This [{{ $user->whatsapp_id }}] Whatsapp Number Already Activated</strong>
                                        </div>
                                    @endif
                                </div>
                            @elseif($user->plan_status == false)
                                <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <h3 class="text-center text-danger">
                                            Complete your plan payment to active Whatsapp Alert.
                                            <a href="{{ route('chose-payment-method') }}" class="btn btn-primary">Pay now</a>
                                        </h3>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <h3 class="text-center text-danger">Whatsapp Alert not enable on your selected plan.</h3>
                                    </div>
                                </div>
                            @endif
                        </div>
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
            document.body.addEventListener('click', copy, true);
            // event handler
            function copy(e) {
                var
                    t = e.target,
                    c = t.dataset.copytarget,
                    inp = (c ? document.querySelector(c) : null);
                if (inp && inp.select) {
                    inp.select();
                    try {
                        document.execCommand('copy');
                        inp.blur();
                        t.classList.add('copied');
                        setTimeout(function() {
                            t.classList.remove('copied');
                        }, 1500);
                    } catch (err) {
                        alert('please press Ctrl/Cmd+C to copy');
                    }
                }
            }

        })();
    </script>
@endsection
