@section('style')
    <link href="{{ asset('assets/admin/card/card-js.min.css') }}" rel="stylesheet" type="text/css" />
@endsection


<div class="border border-primary rounded p-10">
    <div id="card-error" class="text-warning" role="alert"></div>
    <div class="card-js"id="my-card"data-capture-name="true"data-icon-colour="#158CBA">
        <input class="card-number" name="card[number]">
        <input class="expiry-month" name="card[expiryMonth]">
        <input class="expiry-year" name="card[expiryYear]">
        <input class="cvc" name="card[cvv]">
    </div>
</div>

@section('scripts')
    <script src="{{ asset('assets/admin/card/card-js.min.js') }}"></script>
    {{--  cardPaymentForm with form ID example: <form action="{{ route('authorizenet-submit') }}" method="post" id="cardPaymentForm"></form> --}}
    {{--  javascript code written with follow with securionPay   --}}
    <script>
        var $form = $('#cardPaymentForm');
        $form.submit(checkCard);

        function checkCard(e) {
            e.preventDefault();
            $form.find('button').prop('disabled', true);
            var myCard = $('#my-card');
            var number = myCard.CardJs('cardNumber');
            var type = myCard.CardJs('cardType');
            var name = myCard.CardJs('name');
            var month = myCard.CardJs('expiryMonth');
            var year = myCard.CardJs('expiryYear');
            var cvc = myCard.CardJs('cvc');
            var valid = CardJs.isExpiryValid(month, year);
            var errorText = '';
            var isError = false;

            if (number.length < 12) {
                errorText = 'Enter valid Card number.';
                isError = true;
            } else if (month.length == 0) {
                errorText = 'Enter card expiry month.';
                isError = true;
            } else if (year.length == 0) {
                errorText = 'Enter card expiry year.';
                isError = true;
            } else if (cvc.length <= 2) {
                errorText = 'Enter card protected cvc.';
                isError = true;
            } else if (valid === false) {
                errorText = 'Card is Expired, Insert the right Month & Year.';
                isError = true;
            }
            if (isError) {
                $('#card-error').text(errorText);
                $form.find('button').prop('disabled', false);
            } else {
                $('#card-error').text('');
                $form.unbind();
                $form.submit();
            }
        }
    </script>
@endsection
