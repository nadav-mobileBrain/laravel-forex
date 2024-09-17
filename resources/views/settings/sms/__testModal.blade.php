<div class="modal fade" id="TestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-send'></i> Send Test Message !</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('sms-gateway-test') }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="driver" value="{{ $gateway->driver }}">
                    <div class="form-group">
                        <label for="phone" class="control-label"><b>Enter Phone Number: </b></label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter Phone Number" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                    <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>
