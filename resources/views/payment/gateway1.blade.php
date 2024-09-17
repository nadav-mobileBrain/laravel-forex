<div class="row">
    <div class="col-md-12">
        <form action="{{ route('paypal-submit') }}" method="post" name="paypal" id="paypal">
            {{ csrf_field() }}
            <input type="hidden" name="custom" value="{{ $log->order_number }}" />
            <button class="btn btn-primary btn-lg btn-block"><i class="fa fa-paypal"></i> Pay via Paypal</button>
        </form>
    </div>
</div>
