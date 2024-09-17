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
                        @foreach ($signal->chunk(4) as $signals)
                            <div class="row">
                                @foreach ($signals as $k => $p)
                                    <div class="col-md-3">
                                        <div class="card">
                                            <img class="card-img-top" src="{{ asset("assets/images/signal/$p->image") }}" alt="Card image cap">
                                            <div class="card-header d-flex justify-content-between bd-highlight">
                                                <h5 class="card-title">{{ $p->symbol->name }}</h5>
                                                <h5 class="card-title">{{ $p->type->name }}</h5>
                                            </div>
                                            <ul class="list-group list-group-flush font-weight-bold">
                                                <li class="list-group-item d-flex justify-content-between list-group-item-primary">
                                                    <div>SL</div>
                                                    <div>{{ custom($p->id) }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-info">
                                                    <div>Asset</div>
                                                    <div>{{ $p->asset->name }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-info">
                                                    <div>Status</div>
                                                    <div>{{ $p->status->name }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-success">
                                                    <div>Open Price</div>
                                                    <div>{{ $p->entry }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-secondary">
                                                    <div>Take Profit 1</div>
                                                    <div>{{ $p->profit }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-secondary">
                                                    <div>Take Profit 2</div>
                                                    <div>{{ $p->profit_two }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-secondary">
                                                    <div>Take Profit 3</div>
                                                    <div>{{ $p->profit_three }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-warning">
                                                    <div>Stop Loss</div>
                                                    <div>{{ $p->loss }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-info">
                                                    <div>Time Frame</div>
                                                    <div>{{ $p->frame->name }}</div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between list-group-item-info">
                                                    <div>Trade Result</div>
                                                    <div>
                                                        @if ($p->win == null)
                                                            Pending
                                                        @else
                                                            {{ $p->win == 1 ? '+' : '-' }}{{ $p->pips }} Pips
                                                        @endif
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <div>Rating</div>
                                                    <div>
                                                        @php
                                                            if ($p->ratings_count == 0) {
                                                                $final_rating = 0;
                                                            } else {
                                                                $final_rating = round($p->ratings_sum_rating / $p->ratings_count);
                                                            }
                                                        @endphp
                                                        {!! \App\TraitsFolder\CommonTrait::getRating($final_rating) !!} ({{ $p->ratings_count }})
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="card-body">
                                                <div class="btn-group btn-group-sm d-flex justify-content-center" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn btn-success btn-mini result_button" data-toggle="modal" data-target="#ResultModal" data-id="{{ $p->id }}" title="Result"> <i class='fa fa-send'></i> Result</button>
                                                    <a href="{{ route('signal-view', $p->id) }}" class="btn btn-primary btn-mini" title="View"><i class="fa fa-eye"></i> Details</a>
                                                    <a href="{{ route('signal-edit', $p->id) }}" class="btn btn-warning btn-mini" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                                    <button type="button" class="btn btn-danger btn-mini delete_button" data-toggle="modal" data-target="#DelModal" data-id="{{ $p->id }}" title="Delete"> <i class='fa fa-trash'></i> Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        {{ $signal->links('basic.pagination') }}
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Delete ?</strong>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('signal-delete') }}" class="form-inline">
                        {!! csrf_field() !!}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="id" class="delete_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> DELETE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ResultModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-send'></i> Result !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('signal-result') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="signal_id" class="signal_id" value="0">
                        <div class="form-group">
                            <label for="status_id" class="control-label"><b>Update Result : </b></label>
                            <select name="status_id" id="status_id" class="form-control select_status input-lg" required>
                                <option value="">Select One</option>
                                @foreach ($status as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="win" class="control-label"><b>Update Result : </b></label>
                            <select name="win" id="win" class="form-control select_status input-lg" required>
                                <option value="">Select One</option>
                                <option value="1">Win</option>
                                <option value="2">Loss</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pips" class="control-label"><b>Number of Pips: </b></label>
                            <input type="number" name="pips" id="pips" class="form-control" placeholder="Enter number of Pips" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Update Result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on("click", '.delete_button', function(e) {
                var id = $(this).data('id');
                $(".delete_id").val(id);
            });
            $(document).on("click", '.result_button', function(e) {
                var id = $(this).data('id');
                $(".signal_id").val(id);
                var url = `/api/get-signal-result/${id}`;
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(res) {
                        $('#status_id').val(res.status_id);
                        $('#win').val(res.win);
                        $('#pips').val(res.pips);
                    }
                });
            });
        });
    </script>
@endsection
