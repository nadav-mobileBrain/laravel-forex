<div class="row">
    <div class="col-md-12">
        <form action="https://www.coinpayments.net/index.php" method="post">
            <input type="hidden" name="cmd" value="_pay_simple" />
            <input type="hidden" name="item_name" value="Plan payment For - {{ $site_title }}" />
            <input type="hidden" name="custom" value="{{ $log->order_number }}" />
            <input type="hidden" name="want_shipping" value="0" />
            <input type="hidden" name="merchant" value="{{ $log->paymentMethod->val1 }}" />
            <input type="hidden" name="currency" value="{{ $log->paymentMethod->currency }}" />
            <input type="hidden" name="amountf" value="{{ $log->usd }}" />
            <input type="hidden" name="ipn_url" value="{{ route('coinpayment-ipn') }}" />
            <input type="hidden" name="return" value="{{ route('coinpayment-ipn') }}" />
            <input type="hidden" name="success_url" value="{{ route('user-dashboard') }}" />
            <input type="hidden" name="cancel_url" value="{{ route('chose-payment-method') }}" />
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Pay via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>
