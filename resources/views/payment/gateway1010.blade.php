<div class="row">
    <div class="col-md-12">
        <form method='POST' action='https://pay.voguepay.com'>
            <input type="hidden" name='v_merchant_id' value='{{ $log->paymentMethod->val1 }}' />
            <input type="hidden" name='merchant_ref' value='{{ $log->order_number }}' />
            <input type="hidden" name='memo' value='{{ $log->order_number }} Payment Plan' />
            <input type="hidden" name='item_1' value='{{ $log->plan->name }} Plan' />
            <input type="hidden" name='description_1' value='{{ $log->plan->name }} Plan Subscription Payment' />
            <input type="hidden" name='price_1' value='{{ $log->usd }}' />
            <input type="hidden" name='cur' value='{{ $log->paymentMethod->currency }}' />
            <input type="hidden" name='developer_code' value='{{ $log->paymentMethod->val2 }}' />
            <input type="hidden" name='total' value='{{ $log->usd }}' />
            <input type="hidden" name='name' value='{{ $log->user->name }}' />
            <input type="hidden" name='email' value='{{ $log->user->email }}' />
            <input type="hidden" name='phone' value='{{ $log->user->phone }}' />
            <input type="hidden" name='notify_url' value='{{ route('voguepay-ipn') }}' />
            <input type="hidden" name='fail_url' value='{{ route('chose-payment-method') }}' />
            <input type="hidden" name='success_url' value='{{ route('voguepay-ipn') }}' />
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Pay via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>
