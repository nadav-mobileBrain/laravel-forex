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
                            {!! Form::open(['route'=>'about-section','role'=>'form','class'=>'form-horizontal','files'=>true]) !!}
                            <div class="form-body">

                                <div class="row">
                                    <div class="col-md-10 offset-1">

                                        <div class="form-group">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;">About Title</strong></label>
                                            <div class="col-md-12">
                                                <input name="about_title" type="text" class="form-control input-lg" value="{{$section->about_title}}" placeholder="Title" required />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;">About Description</strong></label>
                                            <div class="col-md-12">
                                                <textarea name="about_description" id="area2" cols="10" rows="6" class="form-control input-lg" placeholder="Description" required>{{ $section->about_description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;">Previous About Image</strong></label>
                                            <div class="col-md-12">
                                                <img src="{{ asset('assets/images') }}/{{$section->about_image}}"
                                                     alt="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;">About Image</strong></label>
                                            <div class="col-md-12">
                                                <code>Image Size 670X385 Recommended.</code>
                                                <input name="about_image" type="file" class="form-control input-lg" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> UPDATE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- row -->
                            </div>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            new nicEditor({fullPanel : true,iconsPath : '{{ asset('assets/admin/js/nicEditorIcons.gif') }}'}).panelInstance('area2');
        });
    </script>
@endsection
