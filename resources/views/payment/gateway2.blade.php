<div class="row">
    <div class="col-md-12">
        <form action="https://perfectmoney.is/api/step1.asp" method="POST" id="myform">
            <input type="hidden" name="PAYEE_ACCOUNT" value="{{ $log->paymentMethod->val1 }}">
            <input type="hidden" name="PAYEE_NAME" value="{{ $basic->title }}">
            <input type='hidden' name='PAYMENT_ID' value='{{ $log->order_number }}'>
            <input type="hidden" name="PAYMENT_AMOUNT" value="{{ $log->usd }}">
            <input type="hidden" name="PAYMENT_UNITS" value="{{ $log->paymentMethod->currency }}">
            <input type="hidden" name="STATUS_URL" value="{{ route('perfect-ipn') }}">
            <input type="hidden" name="PAYMENT_URL" value="{{ route('user-dashboard') }}">
            <input type="hidden" name="PAYMENT_URL_METHOD" value="GET">
            <input type="hidden" name="NOPAYMENT_URL" value="{{ route('chose-payment-method') }}">
            <input type="hidden" name="NOPAYMENT_URL_METHOD" value="GET">
            <input type="hidden" name="SUGGESTED_MEMO" value="{{ $basic->title }}">
            <input type="hidden" name="BAGGAGE_FIELDS" value="IDENT">
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Pay via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>
