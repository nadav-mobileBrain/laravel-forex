@extends('layouts.dashboard')
@section('style')
    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>

    <script type="text/javascript">
        bkLib.onDomLoaded(function() { new nicEditor({fullPanel : true}).panelInstance('area1'); });
    </script>

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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> CODE </th>
                                            <th> DESCRIPTION </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="bg-danger">
                                            <td> 1 </td>
                                            <td> @{{name}} </td>
                                            <td> Users Name. It's Automatic Grab from Database. Don't Change it.</td>
                                        </tr>
                                        <tr class="bg-danger">
                                            <td> 2 </td>
                                            <td> @{{message}} </td>
                                            <td> Message Text. Which Text User Receive. Don't Change it.</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('sms-template') }}" method="post" role="form" class="form-horizontal">
                                    {!! csrf_field() !!}
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label><strong style="text-transform: uppercase;">SMS Template</strong></label>
                                            <textarea class="form-control" rows="3" name="sms_tem" placeholder="SMS Template">{!! $basic->sms_tem !!}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary bg-softwarezon-x btn-block btn-lg"><i class="fa fa-send"></i> UPDATE TEMPLATE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
