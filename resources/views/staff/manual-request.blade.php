@extends('layouts.staff')
@section('style')
    <link href="{{ asset('assets/admin/css/bootstrap-fileinput.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
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

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Order Number</th>
                                        <th>User Details</th>
                                        <th>Gateway Name</th>
                                        <th>Total Amount</th>
                                        <th>Plan Details</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($payment as $key => $pm)
                                        <tr class="{{ $pm->status == 0 ? 'bg-warning' : '' }}">
                                            <td><b>{{ ++$key }}</b></td>
                                            <td><b>{{ $pm->order_number }}</b></td>
                                            <td><b>{{ $pm->user->name }}<br>{{ $pm->user->email }}<br>{{ $pm->user->country_code }}{{ $pm->user->phone }}</b></td>
                                            <td><b>{{ $pm->paymentmethod->name }}</b></td>
                                            <td><b>{{ $pm->amount }} {{ $basic->currency }}</b></td>
                                            <td><b>{!! $pm->plan->name !!} <br> {{ $pm->plan->price }} {{ $basic->currency }}</b></td>
                                            <td>
                                                @if ($pm->status == 1)
                                                    <div class="badge badge-primary"><i class="fa fa-check font-medium-1"></i><span class="text-bold-700 text-uppercase"> complete</span></div>
                                                @elseif($pm->status == 2)
                                                    <div class="badge badge-danger"><i class="fa fa-times font-medium-1"></i><span class="text-bold-700 text-uppercase"> Cancel</span></div>
                                                @else
                                                    <div class="badge badge-warning"><i class="fa fa-spinner font-medium-1"></i><span class="text-bold-700 text-uppercase"> pending</span></div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('staff-manual-payment-request-view', $pm->order_number) }}" class="btn btn-primary btn-sm text-bold-700 text-uppercase" title="View">
                                                    <i class='fa fa-eye font-medium-1'></i> <span>View</span>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger bold uppercase delete_button"
                                                        data-toggle="modal" data-target="#DelModal"
                                                        data-id="{{ $pm->id }}" title="Delete">
                                                    <i class='fa fa-trash'></i> Delete
                                                </button>
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

    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Delete ?</strong>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('staff-manual-payment-request-delete') }}" class="form-inline">
                        {!! csrf_field() !!}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="id" class="confirm_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> DELETE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on("click", '.delete_button', function(e) {
                var id = $(this).data('id');
                $(".confirm_id").val(id);
            });
        });
    </script>
@stop
