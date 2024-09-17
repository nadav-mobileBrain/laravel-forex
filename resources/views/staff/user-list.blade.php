@extends('layouts.staff')
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
                                        <th>SL#</th>
                                        <th>Details</th>
                                        <th>Telegram ID</th>
                                        <th>Plan</th>
                                        <th>Email Verify</th>
                                        <th>Phone Verify</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($user as $k => $p)
                                        <tr class="{{ $p->plan_status == 1 ? '' : 'bg-warning ' }}">
                                            <td>{{ $k + $user->firstItem() }}</td>
                                            <td>{{ $p->name }}</td>
                                            <td>
                                                @if ($p->telegram_id != null)
                                                    {{ $p->telegram_id }}
                                                @else
                                                    NULL
                                                @endif
                                            </td>
                                            <td>{{ $p->plan->name }}</td>
                                            <td>
                                                @if ($p->email_status == 1)
                                                    <div class="badge badge-primary">
                                                        <i class='fa fa-check'></i> Verified
                                                    </div>
                                                @else
                                                    <div class="badge badge-danger">
                                                        <i class='fa fa-times'></i> Unverified
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($p->phone_status == 1)
                                                    <div class="badge badge-primary">
                                                        <i class='fa fa-check'></i> Verified
                                                    </div>
                                                @else
                                                    <div class="badge badge-danger">
                                                        <i class='fa fa-times'></i> Unverified
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($p->status == 0)
                                                    <div class="badge badge-primary">
                                                        <i class='fa fa-check'></i> Active
                                                    </div>
                                                @else
                                                    <div class="badge badge-danger">
                                                        <i class='fa fa-times'></i> Block
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($p->plan_status == 1)
                                                    <div class="badge badge-success"><i class="fa fa-check"></i> Payment</div>
                                                @else
                                                    <div class="badge badge-danger"><i class="fa fa-times"></i> Not Payment</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $user->links('basic.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
