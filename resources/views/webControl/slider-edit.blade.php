@extends('layouts.dashboard')
@section('title', 'Slider')
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


                        {!! Form::model($sli,['route'=>['slider-update',$sli->id],'method'=>'put','files'=>true,'class'=>'form-horizontal']) !!}
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Main Title</strong></label>
                                        <div class="col-md-12">
                                            <input name="main_title" value="{{$sli->main_title}}" type="text" class="form-control input-lg" placeholder="Slider Main Title" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Sub Title</strong></label>
                                        <div class="col-md-12">
                                            <input name="sub_title" value="{{$sli->sub_title}}" type="text" class="form-control input-lg" placeholder="Slider Sub Title" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Slider text</strong></label>
                                        <div class="col-md-12">
                                            <input name="slider_text" value="{{$sli->slider_text}}" type="text" class="form-control input-lg" placeholder="Slider Text" required />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Image</strong></label>
                                        <div class="col-md-12">
                                            <div class="" style="width: 100%">
                                                <img src="{{ asset("assets/images/slider/$sli->image") }}" style="width: 100%" alt="" class="img-responsive">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Update Slider Image</strong></label>
                                        <div class="col-md-12">
                                            <input name="image" type="file" class="form-control input-lg" />
                                            <code><b style="color:red; font-weight: bold;margin-top: 10px">ONE IMAGE ONLY | Image Will Resized to 1900 x 730 </b></code>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> SAVE SLIDER</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        @foreach($slider as $s)
            <div class="col-md-6">
                <div class="images">
                    <img class="center-block" src="{{ asset('assets/images/slider') }}/{{ $s->image }}" alt="" style="margin-top: 20px;margin-bottom: 10px;width:100%;">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('slider-edit',$s->id) }}" class="btn btn-success btn-block btn-lg"><i class='fa fa-edit'></i> Edit Slider</a>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-danger btn-block btn-lg delete_button"
                                    data-toggle="modal" data-target="#DelModal"
                                    data-id="{{ $s->id }}">
                                <i class='fa fa-trash'></i> Delete Slider
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" >
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
                    <form method="post" action="{{ route('slider-delete') }}" class="form-inline">
                        {!! csrf_field() !!}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="id" class="abir_id" value="0">

                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> DELETE</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('scripts')

    <script>
        $(document).ready(function () {
            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                $(".abir_id").val(id);
            });
        });
    </script>

@endsection

