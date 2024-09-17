@extends('layouts.dashboard')
@section('style')
    <link href="{{ asset('assets/admin/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
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

                        {!! Form::model($basic, ['route' => ['referral-setting-update', $basic->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'form-horizontal']) !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label for="referral_commission_status"><strong style="text-transform: uppercase;">Referral Commission Status:</strong></label>
                                <input id="referral_commission_status" data-toggle="toggle" {{ $basic->referral_commission_status == 1 ? 'checked' : '' }} data-onstyle="success" data-offstyle="danger" data-on="Enable" data-off="Disable" data-width="100%" type="checkbox" name="referral_commission_status">
                            </div>

                            <div class="form-group">
                                <label for="total_level"><strong style="text-transform: uppercase;">Total Referral Level:</strong></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="total_level" id="levelCount" value="{{ $total_level }}" placeholder="Enter Number of levels" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" onclick="updateForm()" type="button"><i class="fa fa-cog"></i> Create Referral Level</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div id="levels">
                                @foreach ($levels as $key => $level)
                                    @php ++$key @endphp
                                    <div class="form-group">
                                        <label for="commission{{ $key }}"><strong style="text-transform: uppercase;">Level ({{ $key }}) Percentage:</strong></label>
                                        <div class="input-group">
                                            <input id="commission{{ $key }}" class="form-control bold input-lg" name="commission[]" value="{{ $level->commission }}" placeholder="Enter Level {{ $key }} commission Percentage" type="number" step="0.001" required>
                                            <span class="input-group-addon"><strong><i class="fa fa-percent"></i></strong></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary bg-softwarezon-x btn-block btn-lg"><i class="fa fa-send"></i> UPDATE COMMISSION LEVEL</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>
    <script type="text/javascript">
        function updateForm() {
            var parent = document.getElementById('levels');
            var count = document.getElementById('levelCount').value || 0;
            parent.innerHTML = '';
            for (let i = 0; i < count; i++) {
                parent.innerHTML += `<div class="form-group">
                    <label for="commission${i+1}"><strong style="text-transform: uppercase;">Level (${i+1}) Percentage:</strong></label>
                    <div class="input-group">
                        <input id="commission${i+1}" class="form-control bold input-lg" name="commission[]" value="" placeholder="Enter Level ${i+1} commission Percentage" type="number" step="0.001" required>
                        <span class="input-group-addon"><strong><i class="fa fa-percent"></i></strong></span>
                    </div>
                </div>`;
            }
        }
    </script>
@endsection
