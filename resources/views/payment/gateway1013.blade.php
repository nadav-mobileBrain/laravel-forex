<div class="row">
    <div class="col-md-12">
        <button type="submit" onclick="payWithMonnify()" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Pay via {{ $log->paymentMethod->name }}</button>
    </div>
</div>
@section('scripts')
    <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
    <script>
        let closedPayment = function(data) {
            alert('Don\'t Refresh this page until it back to dashboard.');
        }
        let successPayment = function(response) {
            if (response.paymentReference) {
                window.location.href = '{{ route('monnify-ipn', ['custom' => $log->order_number]) }}';
            } else {
                window.location.href = '{{ route('chose-payment-method') }}';
            }
        }

        function payWithMonnify() {
            MonnifySDK.initialize({
                amount: '{{ $log->usd }}',
                currency: '{{ $log->paymentMethod->currency }}',
                reference: '{{ $log->order_number }}',
                customerFullName: "{{ $log->user->name }}",
                customerEmail: "{{ $log->user->email }}",
                apiKey: "{{ $log->paymentMethod->val1 }}",
                contractCode: "{{ $log->paymentMethod->val3 }}",
                paymentDescription: "{{ $log->plan->name }} Plan Subscription Payment",
                onComplete: successPayment,
                onClose: closedPayment
            });
        }
    </script>
@endsection
