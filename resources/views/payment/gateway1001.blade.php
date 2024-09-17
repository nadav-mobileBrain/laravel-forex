<div class="row">
    <div class="col-md-12">
        <form action="https://checkout.flutterwave.com/v3/hosted/pay" method="post" name="flutterwave" id="flutterwave">
            <input type="hidden" name="custom" value="{{ $log->order_number }}" />
            <input type="hidden" name="public_key" value="{{ $log->paymentMethod->val1 }}" />
            <input type="hidden" name="customer[email]" value="{{ $log->user->email }}" />
            <input type="hidden" name="customer[name]" value="{{ $log->user->name }}" />
            <input type="hidden" name="tx_ref" value="{{ $log->order_number }}" />
            <input type="hidden" name="amount" value="{{ $log->usd }}" />
            <input type="hidden" name="currency" value="{{ $log->paymentMethod->currency }}" />
            <input type="hidden" name="redirect_url" value="{{ route('flutterwave-ipn') }}" />
            <input type="hidden" name="customizations[title]" value="{{ $site_title }}" />
            <input type="hidden" name="customizations[logo]" value="{{ asset('assets/images/logo.png') }}" />
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Pay via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>
