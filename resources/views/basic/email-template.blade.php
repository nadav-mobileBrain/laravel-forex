@extends('layouts.dashboard')
@section('style')
    <script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            new nicEditor({fullPanel : true,iconsPath : '{{ asset('assets/admin/js/nicEditorIcons.gif') }}'}).panelInstance('area1');
        });
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
                                    <div class="card">
                                        <div class="card-content collpase show">
                                            <div class="card-body">
                                                <div class="col-md-12">

                                                    <div class="table-scrollable bg-info">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th> # </th>
                                                                <th> CODE </th>
                                                                <th> DESCRIPTION </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr>
                                                                <td> 1 </td>
                                                                <td> @{{message}} </td>
                                                                <td> This is Message Text. Which user Receive</td>
                                                            </tr>
                                                            <tr>
                                                                <td> 2 </td>
                                                                <td> @{{name}} </td>
                                                                <td> Users Name. It's Automatic Grab from Database.</td>
                                                            </tr>
                                                            <tr>
                                                                <td> 3 </td>
                                                                <td> @{{site_title}} </td>
                                                                <td> Site Title. It's Automatic Grab from Database. </td>
                                                            </tr>
                                                            <tr>
                                                                <td> 4 </td>
                                                                <td> Change Email Logo </td>
                                                                <td> Edit as <code>html</code> Scroll bellow and Replace <code>src</code> tag URL with Your Logo URL.</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END SAMPLE TABLE PORTLET-->
                                </div>

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-content collpase show">
                                            <div class="card-body">

                                                <form action="{{ route('email-template') }}" method="post" role="form" class="form-horizontal">
                                                    {!! csrf_field() !!}
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Email Template</strong></label>
                                                                    <div class="col-md-12">
                                                                        <textarea id="area1" class="form-control" rows="5" name="email_body">{{ $email->email_body }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <button type="submit" class="btn btn-primary bg-softwarezon-x btn-block btn-lg"><i class="fa fa-send"></i> UPDATE TEMPLATE</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- END SAMPLE TABLE PORTLET-->
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
