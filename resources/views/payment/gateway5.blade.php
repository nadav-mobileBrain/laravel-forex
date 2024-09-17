<div class="row">
    <div class="col-md-12">
        <form action="https://www.moneybookers.com/app/payment.pl" method="post">
            <input type="hidden" name="pay_to_email" value="{{ $log->paymentMethod->val1 }}" />
            <input type="hidden" name="status_url" value="{{ route('skrill-ipn') }}" />
            <input type="hidden" name="return_url" value="{{ route('user-dashboard') }}" />
            <input type="hidden" name="cancel_url" value="{{ route('chose-payment-method') }}" />
            <input type="hidden" name="language" value="EN" />
            <input name="transaction_id" type="hidden" value="{{ $log->order_number }}">
            <input type="hidden" name="amount" value="{{ $log->usd }}" />
            <input type="hidden" name="currency" value="{{ $log->paymentMethod->currency }}" />
            <input type="hidden" name="detail1_description" value="{{ $site_title }}" />
            <input type="hidden" name="detail1_text" value="Plan payment For - {{ $site_title }}" />
            <input type="hidden" name="logo_url" value="{{ asset('assets/images/logo.png') }}" />
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Pay via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>
