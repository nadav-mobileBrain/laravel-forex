@extends('layouts.dashboard')
@section('style')
@endsection

@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('speciality-control') }}" class="btn btn-primary"><i class="fa fa-eye"></i> All Speciality</a>
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

                        <form class="form-horizontal" action="{{ route('speciality-create') }}" method="post" role="form">

                            {!! csrf_field() !!}
                            <div class="form-body">
                                <div class="form-group row">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Specialty Title</strong></label>
                                    <div class="col-md-12">
                                        <input class="form-control input-lg" name="name" placeholder="Specialty Title" type="text" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Font Awesome icon</strong></label>
                                    <div class="col-md-12">
                                        <input class="form-control input-lg" name="icon" placeholder=" Font awesome icon" type="text" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Specialty Description</strong></label>
                                    <div class="col-md-12">
                                        <textarea id="area1" class="form-control" placeholder="Specialty Description" rows="5" name="description"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-plus"></i> ADD SPECIALTY</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
