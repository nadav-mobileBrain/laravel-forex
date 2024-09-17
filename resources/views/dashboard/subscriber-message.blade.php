@extends('layouts.dashboard')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/select2.min.css')}}">
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

                        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('subscriber-message-submit')}}">
                            {{csrf_field()}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label"><b>Message Title : </b></label>
                                            <div class="col-sm-12">
                                                <input name="title" value="" class="form-control input-lg" type="text" placeholder="Message Title" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label"><b>Select Subscriber :  All <input type="checkbox" id="checkbox" ></b></label>
                                            <div class="col-sm-12">
                                                <select name="subscriber_ids[]" id="e1" class="form-control input-lg select2-multi" multiple="multiple" >
                                                    @foreach($subscriber as $d)
                                                        <option value="{{$d->id}}" style="color: #000">{{$d->email}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-12 control-label"><b>Message :</b> </label>
                                            <div class="col-sm-12">
                                                <textarea name="message" id="area1" rows="8" class="form-control input-lg"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary bg-softwarezon-x btn-lg btn-block "> <i class="fa fa-send"></i> Send Message</button>
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
@section('scripts')
    <script src="{{asset('assets/admin/js/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/select2.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $('.select2-multi').select2();

        $("#checkbox").click(function(){
            if($("#checkbox").is(':checked') ){
                $("#e1 > option").prop("selected","selected");// Select All Options
                $("#e1").trigger("change");// Trigger change to select 2
            }else{
                $("#e1 > option").removeAttr("selected");
                $("#e1").trigger("change");// Trigger change to select 2
            }
        });
    </script>
    <script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            new nicEditor({fullPanel : true,iconsPath : '{{ asset('assets/admin/js/nicEditorIcons.gif') }}'}).panelInstance('area1');
        });
    </script>
@endsection
