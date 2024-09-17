@extends('layouts.dashboard')

@section('import_style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.fancybox.min.css') }}" />
    <script src="{{ asset('assets/admin/js/jquery.fancybox.min.js') }}"></script>
@endsection
@section('style')
    <style>
        .form-control {
            padding: 0.66rem 0.75rem;
        }
    </style>
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

                        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('signal-update') }}">
                            <input type="hidden" name="signal_id" value="{{ $signal->id }}">
                            {{ csrf_field() }}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Signal Title : </b></label>
                                                    <div class="col-sm-12">
                                                        <input name="title" value="{{ $signal->title }}" class="form-control input-lg" type="text" placeholder="Signal Title" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Signal Plan : All <input type="checkbox" id="checkbox"></b></label>
                                                    <div class="col-sm-12">
                                                        <select name="service_id[]" id="e1" class="form-control select2-multi" data-placeholder="Select Plans" multiple="multiple">
                                                            @foreach ($plan as $d)
                                                                @if (in_array($d->id, $signalPlan))
                                                                    <option value="{{ $d->id }}" style="color: #000" selected>{{ $d->name }}</option>
                                                                @else
                                                                    <option value="{{ $d->id }}" style="color: #000">{{ $d->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Select Assets : </b></label>
                                                    <div class="col-sm-12">
                                                        <select name="asset_id" id="" class="form-control select_asset input-lg" required>
                                                            @foreach ($asset as $as)
                                                                <option value="{{ $as->id }}" {{ $signal->asset_id == $as->id ? 'selected' : '' }}>{{ $as->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Select Symbol : </b></label>
                                                    <div class="col-sm-12">
                                                        <select name="symbol_id" id="" class="form-control select_symbol input-lg" required>
                                                            @foreach ($symbol as $sm)
                                                                <option value="{{ $sm->id }}" {{ $signal->symbol_id == $sm->id ? 'selected' : '' }}>{{ $sm->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Select Type : </b></label>
                                                    <div class="col-sm-12">
                                                        <select name="type_id" id="" class="form-control select_type input-lg" required>
                                                            @foreach ($type as $ty)
                                                                <option value="{{ $ty->id }}" {{ $signal->type_id == $ty->id ? 'selected' : '' }}>{{ $ty->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Select Time Frame : </b></label>
                                                    <div class="col-sm-12">
                                                        <select name="frame_id" id="" class="form-control select_frame input-lg" required>
                                                            @foreach ($frame as $fr)
                                                                <option value="{{ $fr->id }}" {{ $signal->frame_id == $fr->id ? 'selected' : '' }}>{{ $fr->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Open Price : </b></label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="entry" value="{{ $signal->entry }}" id="" class="form-control input-lg" placeholder="Open Price" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Stop Loss : </b></label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="loss" id="" value="{{ $signal->loss }}" class="form-control input-lg" placeholder="Stop Loss">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Select Status : </b></label>
                                                    <div class="col-sm-12">
                                                        <select name="status_id" id="" class="form-control select_status input-lg" required>
                                                            @foreach ($status as $st)
                                                                <option value="{{ $st->id }}" {{ $signal->status_id == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Take Profit 1: </b></label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="profit" id="" value="{{ $signal->profit }}" class="form-control input-lg" placeholder="Take Profit 1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Take Profit 2: </b></label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="profit_two" value="{{ $signal->profit_two }}" id="" class="form-control input-lg" placeholder="Take Profit 2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 control-label"><b>Take Profit 3: </b></label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="profit_three" value="{{ $signal->profit_three }}" id="" class="form-control input-lg" placeholder="Take Profit 3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($signal->image != null)
                                            <div class="form-group row">
                                                <label class="col-sm-12 control-label"><b>Signal image : </b></label>
                                                <div class="col-sm-12">
                                                    <a data-fancybox="gallery" href="{{ asset("assets/images/signal/$signal->image") }}">
                                                        <img src="{{ asset("assets/images/signal/$signal->image") }}" style="width: 30%" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group row">
                                            <label class="col-sm-12 control-label"><b>{{ $signal->image == null ? 'Select' : 'Update' }} Signal image : </b></label>
                                            <div class="col-sm-12">
                                                <input name="image" value="" class="form-control input-lg" type="file">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-12 control-label"><b>Signal Description :</b> </label>
                                            <div class="col-sm-12">
                                                <textarea name="description" id="area1" rows="4" class="form-control input-lg">{{ $signal->description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <button type="submit" onclick="nicEditors.findEditor('area1').saveContent();" class="btn btn-primary bg-softwarezon-x btn-lg btn-block "> <i class="fa fa-send"></i> Update Signal</button>
                                            </div>
                                        </div>
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
@section('import_scripts')
    <script src="{{ asset('assets/admin/js/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
@endsection
@section('scripts')
    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            new nicEditor({
                fullPanel: true,
                iconsPath: '{{ asset('assets/admin/js/nicEditorIcons.gif') }}'
            }).panelInstance('area1');
        });
        $('.select2-multi').select2();
        $('.select_asset,.select_frame,.select_status,.select_symbol,.select_type').select2();

        $("#checkbox").click(function() {
            if ($("#checkbox").is(':checked')) {
                $("#e1 > option").prop("selected", "selected"); // Select All Options
                $("#e1").trigger("change"); // Trigger change to select 2
            } else {
                $("#e1 > option").removeAttr("selected");
                $("#e1").trigger("change"); // Trigger change to select 2
            }
        });
    </script>
@endsection
