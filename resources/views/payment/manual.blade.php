<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="text-center">
            <h4>Send Total Amount Following Details:</h4><br>
            <h3>{!! $log->paymentMethod->val1 !!}</h3>
            <hr>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form action="{{ route('manual-payment-submit') }}" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="payment_log_id" value="{{ $log->id }}">
                    <div class="form-group row">
                        <label class="col-md-12 label-control text-left" for="projectinput1"><b>Upload Proof Documents : <code>Multiple Image allowed</code></b></label>
                        <div class="col-md-12">
                            <input type="file" id="projectinput1" class="form-control" name="images[]" multiple required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 label-control text-left" for="projectinput1"><b>Write your Message : </b></label>
                        <div class="col-md-12">
                            <textarea id="projectinput1" class="form-control" rows="4" placeholder="Write your Message" name="message"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-lg bg-softwarezon-x border-0 btn-block"><i class="fa fa-send"></i> Send Payment Request</button>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>
