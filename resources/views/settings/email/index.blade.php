@extends('layouts.dashboard')
@section('style')
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
                        <div class="alert alert-warning bg-warning text-white" role="alert">
                            <h4 class="alert-heading"><i class="fa fa-warning"></i> Warning!</h4>
                            <h6>Enable only this Driver/Gateway which you tested otherwise user may hamper. System with take randomly one driver to provided this service.</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Driver Logo</th>
                                        <th>Driver Name</th>
                                        <th>Updated At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($drivers as $key => $driver)
                                        <tr class="{{ $driver->status == 0 ? 'bg-warning' : '' }}">
                                            <td><b>{{ ++$key }}</b></td>
                                            <td><img src="{{ asset('assets/images/settings/email') }}/{{ $driver->image }}" width="120px" alt=""></td>
                                            <td><b>{{ $driver->name }}</b></td>
                                            <td>{{ $driver->updated_at->format('dS F, Y') }}</td>
                                            <td>
                                                @if ($driver->status)
                                                    <div class="badge badge-primary"><i class="fa fa-check font-medium-1"></i><span class="text-bold-700 text-uppercase">Activate</span></div>
                                                @else
                                                    <div class="badge badge-danger"><i class="fa fa-times font-medium-1"></i><span class="text-bold-700 text-uppercase">Deactivate</span></div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('edit-email-drivers', $driver->id) }}" class="btn btn-primary text-bold-700 btn-mini text-uppercase"><i class='fa fa-send font-medium-1'></i> <span>Edit Driver</span></a>
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
