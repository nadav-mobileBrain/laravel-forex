@extends('layouts.dashboard')
@section('style')
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
                                                <td><b> Launch App and Search as <code class="white">BotFather</code></b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 3</b> </td>
                                                <td><b> Start Conversion As <code class="white">/newbot</code></b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 4</b> </td>
                                                <td><b> Then Chose a Bot Name and Press Enter.</b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 5</b> </td>
                                                <td><b> Now choose a username for your bot. It must end in `bot`. Like this, for example: TetrisBot or tetris_bot.</b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 6</b> </td>
                                                <td><b> Then You Found Your Bot URL and API Key. Copy This and Paste Bellow.</b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 7</b> </td>
                                                <td><b> Again Send <code class="white">/setdescription</code> and Chose Your Bot and Write your Bot Description.</b></td>
                                            </tr>
                                            <tr>
                                                <td> <b>#Step 8</b> </td>
                                                <td><b> Again Send <code class="white">/setprivacy</code> and Chose Your Bot and Set Bot Privacy.</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="card">
                                    <div class="card-content collpase show">
                                        <div class="card-body">

                                            <form class="form-horizontal" action="{{ route('update-template-config') }}" method="post" role="form">
                                                {!! csrf_field() !!}
                                                <div class="form-body">

                                                    <div class="form-group">
                                                        <label class="col-md-12"><strong>Telegram Bot URL</strong><br></label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" value="{{ $driver->url }}" name="telegram_url" placeholder="Bot URL">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-12"><strong>Telegram Bot Token</strong><br></label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" value="{{ $driver->token }}" name="telegram_token" placeholder="Bot Token">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> UPDATE</button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
