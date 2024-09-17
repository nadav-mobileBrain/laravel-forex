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
                        <div class="table-responsive">

                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                <tr>
                                    <th>ID#</th>
                                    <th>Created At</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Password</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($staff as $k => $p)
                                    <tr>
                                        <td>{{ ++$k }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->created_at)->format('dS-F-Y') }}</td>
                                        <td>{{$p->name}}</td>
                                        <td>{{$p->email}}</td>
                                        <td>{{$p->country_code}}{{$p->phone}}</td>
                                        <td>
                                            @if($p->status == 1)
                                                <div class="badge badge-success"><i class="fa fa-check"></i> Active</div>
                                            @else
                                                <div class="badge badge-danger"><i class="fa fa-times"></i> Deactive</div>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-grd-primary btn-sm bold uppercase password_button"
                                                    data-toggle="modal" data-target="#PasswordModal"
                                                    data-id="{{$p->id}}" title="Update Password">
                                                <i class='fa fa-key'></i> Update Password
                                            </button>
                                        </td>
                                        <td>
                                            <a href="{{ route('staff-edit',$p->id) }}" class="btn btn-primary btn-sm bold uppercase" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$staff->links('basic.pagination')}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="PasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-key"></i> Update Password.!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form class="form form-horizontal" action="{{ route('staff-password-update') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-body">

                            <div class="form-group row">
                                <label class="col-md-3 label-control text-right" for="projectinput1"><b>New Password : </b></label>
                                <div class="col-md-8">
                                    <input type="password" id="projectinput1" class="form-control font-weight-bold" value="" placeholder="New Password" name="password" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control text-right" for="projectinput1"><b>Confirm Password : </b></label>
                                <div class="col-md-8">
                                    <input type="password" id="projectinput1" class="form-control font-weight-bold" value="" placeholder="Confirm Password" name="password_confirmation" required>
                                </div>
                            </div>
                            <input type="hidden" name="id" class="password_id" value="0">
                            <div class="form-group row">
                                <div class="col-md-8 offset-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Update Password</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on("click", '.password_button', function (e) {
                var id = $(this).data('id');
                $(".password_id").val(id);
            });
        });
    </script>
@endsection
