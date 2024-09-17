@extends('layouts.user')
@section('content')

    <div class="page-body">
        <div class="row no-gutters">
            @foreach ($plan as $k => $p)
                @if ($p->price_type == 0 and \Illuminate\Support\Facades\Auth::user()->free_plan_status == 1)
                    @continue
                @else
                    <div class="col-md-4 pr-1">
                        <div class="list-group text-center my-3">
                            <div class="list-group-item text-white  bg-primary">
                                <h4 class="text-center text-uppercase  font-weight-bold my-1">{{ $p->name }}</h4>
                            </div>
                            <div class="list-group-item text-uppercase font-weight-bold">
                                <h3>
                                    @if ($p->price == 0)
                                        FREE
                                    @else
                                        {{ $basic->symbol }}{{ $p->price }}
                                    @endif
                                </h3>
                            </div>
                            @if ($p->plan_type == 0)
                                <a href="#" class="list-group-item">
                                    <h4>{{ $p->duration }} - Days</h4>
                                </a>
                            @else
                                <a href="#" class="list-group-item">
                                    <h4>Unlimited</h4>
                                </a>
                            @endif
                            <a href="#" class="list-group-item">
                                <h4>Dashboard Signal - {{ $p->dashboard_status == 1 ? 'YES' : 'NO' }}</h4>
                            </a>
                            <a href="#" class="list-group-item">
                                <h4>Whatsapp Alert - {{ $p->whatsapp_status == 1 ? 'YES' : 'NO' }}</h4>
                            </a>
                            <a href="#" class="list-group-item">
                                <h4>Telegram Alert - {{ $p->telegram_status == 1 ? 'YES' : 'NO' }}</h4>
                            </a>
                            <a href="#" class="list-group-item">
                                <h4>Email Alert - {{ $p->email_status == 1 ? 'YES' : 'NO' }}</h4>
                            </a>
                            <a href="#" class="list-group-item">
                                <h4>SMS Alert - {{ $p->sms_status == 1 ? 'YES' : 'NO' }}</h4>
                            </a>
                            <a href="#" class="list-group-item">
                                <h4>Phone Consulting - {{ $p->call_status == 1 ? 'YES' : 'NO' }}</h4>
                            </a>
                            @if ($p->contents)
                                @foreach ($p->contents as $content)
                                    <a href="#" class="list-group-item">
                                        <h4>{{ $content }}</h4>
                                    </a>
                                @endforeach
                            @endif
                            <a href="#" class="list-group-item">
                                <h4>Support - {{ $p->support }}</h4>
                            </a>

                            <div class="list-group-item">
                                <button type="button" class="btn btn-grd-primary btn-lg btn-block text-truncate delete_button" data-toggle="modal" data-target="#DelModal" data-id="{{ $p->id }}"> <i class="fa fa-send"></i> Upgrade Now</button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <!---ROW-->
    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">
                    <strong>Are you sure want to Upgrade Plan ?</strong>
                </div>

                <div class="modal-footer">
                    <form method="post" action="{{ route('upgrade-plan-submit') }}" class="form-inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="delete_id" id="delete_id" class="delete_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary deleteButton"><i class="fa fa-send"></i> Yes Sure</button>
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
                $("#delete_id").val(id);
            });
        });
    </script>
@endsection
