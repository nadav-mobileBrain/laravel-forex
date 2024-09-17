@extends('layouts.dashboard')
@section('style')
    <link href="{{ asset('assets/admin/css/bootstrap-fileinput.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@stop

@section('content')

    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('manual-payment-method-create') }}" class="btn btn-primary bold"><i class="fa fa-plus"></i> Add New Gateway</a>
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
                                    <th>#SL</th>
                                    <th>Gateway Name</th>
                                    <th>Gateway Display Image</th>
                                    <th>Conversion Rate</th>
                                    <th>Payment Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($payment as $key => $pm)
                                    <tr class="{{$pm->status == 0 ? 'bg-warning' : ''}}">
                                        <td><b>{{ ++$key }}</b></td>
                                        <td><b>{{ $pm->name }}</b></td>
                                        <td><img src="{{ asset('assets/images/payment') }}/{{ $pm->image }}" width="40%" alt=""></td>
                                        <td><b>1 USD = {{$pm->rate}} {{ $basic->currency }}</b></td>
                                        <td><b>{!! $pm->val1 !!}</b></td>
                                        <td>
                                            @if($pm->status == 1)
                                                <div class="badge badge-primary"><i class="fa fa-check font-medium-1"></i><span class="text-bold-700 text-uppercase">Activate</span></div>
                                            @else
                                                <div class="badge badge-danger"><i class="fa fa-times font-medium-1"></i><span class="text-bold-700 text-uppercase">Deactivate</span></div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('manual-payment-method-edit',$pm->id) }}" class="btn btn-primary text-bold-700 btn-mini text-uppercase" title="Edit Gateway">
                                                <i class='fa fa-send font-medium-1'></i> <span>Edit</span>
                                            </a>
                                        </td>
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


@endsection
@section('scripts')

@stop
