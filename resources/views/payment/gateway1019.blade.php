@section('style')
    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.7.3/moyasar.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
    <script src="https://cdn.moyasar.com/mpf/1.7.3/moyasar.js"></script>
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="mysr-form"></div>
    </div>
</div>
@section('scripts')
    <script>
        @if ($log->paymentMethod->currency == 'SAR')
            @php $payable = $log->usd * 100 @endphp
        @elseif ($log->paymentMethod->currency == 'KWD')
            @php $payable = $log->usd * 1000 @endphp
        @else
            @php $payable = $log->usd @endphp
        @endif
        Moyasar.init({
            element: '.mysr-form',
            amount: '{{ $payable }}',
            currency: '{{ $log->paymentMethod->currency }}',
            description: '{{ $log->plan->name }} Plan Subscription Payment',
            publishable_api_key: '{{ $log->paymentMethod->val2 }}',
            callback_url: '{{ route('moyasar-ipn', ['custom' => $log->order_number]) }}',
            methods: [
                'creditcard',
                'stcpay'
            ],
        })
    </script>
@endsection
