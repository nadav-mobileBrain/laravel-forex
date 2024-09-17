<div class="row">
    <div class="col-md-12">
        <form action="https://www.cashmaal.com/Pay/" method="POST">
            {{--  pay_method (cm,pm,jca,epa,btc) if blank user will select on CM  --}}
            <input type="hidden" name="pay_method" value="cm">
            <input type="hidden" name="amount" value="{{ $log->usd }}">
            {{--  currency (PKR,USD)  --}}
            <input type="hidden" name="currency" value="{{ $log->paymentMethod->currency }}">
            <input type="hidden" name="succes_url" value="{{ route('user-dashboard') }}">
            <input type="hidden" name="cancel_url" value="{{ route('chose-payment-method') }}">
            <input type="hidden" name="client_email" value="{{ $log->user->email }}">
            <input type="hidden" name="web_id" value="{{ $log->paymentMethod->val2 }}">
            <input type="hidden" name="order_id" value="{{ $log->order_number }}">
            <input type="hidden" name="addi_info" value="{{ $log->plan->name }} Plan Subscription Payment">
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Pay via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>
