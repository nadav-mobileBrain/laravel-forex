@extends('layouts.staff')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/responsive.bootstrap4.min.css') }}" />
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

                            <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="sample_1">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>Details</th>
                                        <th>Telegram ID</th>
                                        <th>Balance</th>
                                        <th>Plan</th>
                                        <th>Email Verify</th>
                                        <th>Phone Verify</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
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
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-exclamation-triangle"></i> Confirmation.!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Do this ?</strong>
                </div>

                <div class="modal-footer">
                    <form method="post" action="{{ route('staff-user-block') }}" class="form-inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="block_id" class="block_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Yes sure</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="EmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-exclamation-triangle"></i> Confirmation.!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Do This ?</strong>
                </div>

                <div class="modal-footer">
                    <form method="post" action="{{ route('staff-email-block') }}" class="form-inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="email_id" class="email_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Yes sure</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="PhoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-exclamation-triangle"></i> Confirmation.!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Do This ?</strong>
                </div>

                <div class="modal-footer">
                    <form method="post" action="{{ route('staff-phone-block') }}" class="form-inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="phone_id" class="phone_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Yes sure</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="ConModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-trash'></i> Delete !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Delete ?</strong>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('staff-user-delete') }}" class="form-inline">
                        {!! csrf_field() !!}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="id" class="confirm_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> DELETE</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/dataTables.responsive.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#sample_1').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('staff-manage-user') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id',
                    },
                    {
                        data: 'user_details',
                        name: 'user_details',
                        orderable: false
                    },
                    {
                        data: 'telegram_id',
                        name: 'telegram_id',
                        orderable: false
                    },
                    {
                        data: 'balance',
                        name: 'balance',
                        orderable: false
                    },
                    {
                        data: 'plan.name',
                        name: 'plan.name',
                        orderable: false
                    },
                    {
                        data: 'email_status',
                        name: 'email_status',
                        orderable: false
                    },
                    {
                        data: 'phone_status',
                        name: 'phone_status',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false
                    },
                    {
                        data: 'plan_status',
                        name: 'plan_status',
                        orderable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $(document).on("click", '.block_button', function(e) {
                var id = $(this).data('id');
                $(".block_id").val(id);
            });
            $(document).on("click", '.email_button', function(e) {
                var id = $(this).data('id');
                $(".email_id").val(id);
            });
            $(document).on("click", '.phone_button', function(e) {
                var id = $(this).data('id');
                $(".phone_id").val(id);
            });
            $(document).on("click", '.confirm_button', function(e) {
                var id = $(this).data('id');
                $(".confirm_id").val(id);
            });
        });
    </script>
@endsection
