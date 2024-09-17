<form action="{{ route('stripe-submit') }}" method="post" id="payment-form">
    @csrf
    <input type="hidden" name="custom" value="{{ $log->order_number }}">
    <div class="border border-primary rounded p-10">
        <div id="card-element"></div>
        <div id="card-errors" class="text-warning" role="alert"></div>
    </div>
    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block mt-3"><i class="fa fa-credit-card"></i> Pay via Card</button>
</form>
@section('style')
    <script src="https://js.stripe.com/v3/"></script>
@endsection
@section('scripts')
    <script>
        var stripe = Stripe('{{ $log->paymentMethod->val2 }}');
        var elements = stripe.elements();
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                },
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        var card = elements.create('card', {
            hidePostalCode: true,
            style: style
        });
        card.mount('#card-element');

        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
                document.getElementById("submitBtn").disabled = true;
            } else {
                displayError.textContent = '';
                document.getElementById("submitBtn").disabled = false;
            }
        });
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    document.getElementById("submitBtn").disabled = false;
                    var token = result.token;
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        });
    </script>
@endsection
