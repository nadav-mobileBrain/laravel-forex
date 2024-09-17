<div class="row">
    <div class="col-md-12">
        <form action="{{ route('braintree-ipn') }}" method="post" id="payment-form">
            @csrf
            <input type="hidden" name="custom" value="{{ $log->order_number }}">
            <div class="bt-drop-in-wrapper">
                <div id="bt-dropin"></div>
            </div>
            <input id="token" name="token" type="hidden" />
            <button type="submit" class="btn btn-primary btn-lg btn-block mt-3"><i class="fa fa-credit-card"></i> Pay {{ $log->usd }} {{ $log->paymentMethod->currency }} via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>
@section('style')
    <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script>
@endsection

@section('scripts')
    <script>
        var form = document.querySelector('#payment-form');

        braintree.dropin.create({
            authorization: '{{ $token }}',
            selector: '#bt-dropin',
        }, function(err, instance) {
            if (err) {
                console.log('Create Error', err);
                return;
            }
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                instance.requestPaymentMethod(function(err, payload) {
                    if (err) {
                        console.log('Request Payment Method Error', err);
                        return;
                    }
                    document.querySelector('#token').value = payload.nonce;
                    form.submit();
                })
            });
        });
    </script>
@endsection
