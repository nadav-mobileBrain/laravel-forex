@extends('layouts.dashboard')
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

                            <form class="form form-horizontal" action="" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">

                                    <div class="form-group row">
                                        <label class="col-md-3 label-control text-right font-weight-bold" for="projectinput1">Old Password: </label>
                                        <div class="col-md-8">
                                            <input type="password" id="projectinput1" class="form-control" value="" placeholder="Old Password" name="current_password" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control text-right font-weight-bold" for="projectinput2">New Password : </label>
                                        <div class="col-md-8">
                                            <input type="password" id="projectinput2" class="form-control" value="" placeholder="New Password" name="password" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control text-right font-weight-bold" for="projectinput2">Confirm Password : </label>
                                        <div class="col-md-8">
                                            <input type="password" id="projectinput2" class="form-control" value="" placeholder="Confirm Password" name="password_confirmation" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-8 offset-3">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="ft-navigation"></i> Update Now</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection