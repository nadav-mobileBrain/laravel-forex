<div class="row">
    <div class="col-md-12">
        <form action="{{ route('securionpay-submit') }}" method="post" id="payment-form">
            @csrf
            <input type="hidden" name="custom" value="{{ $log->order_number }}">
            <div class="form-body border border-primary rounded p-10">
                <div id="payment-error" class="text-warning" role="alert"></div>
                <div class="form-row">
                    <div class="form-group col-md-6" style="margin-bottom: 0px">
                        <label>Card Number</label>
                        <div data-securionpay="number"></div>
                    </div>
                    <div class="form-group col-md-3" style="margin-bottom: 0px">
                        <label>Expiration</label>
                        <div data-securionpay="expiry"></div>
                    </div>
                    <div class="form-group col-md-3" style="margin-bottom: 0px">
                        <label>CVC</label>
                        <div data-securionpay="cvc"></div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block mt-3"><i class="fa fa-credit-card"></i> Pay via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>
@section('scripts')
    <script type="text/javascript" src="https://js.securionpay.com/v2/securionpay.js"></script>

    <script type="text/javascript">
        var securionPay = SecurionPay('{{ $log->val1 }}');
        var components = securionPay.createComponentGroup().automount("#payment-form");
        var $form = $('#payment-form');
        $form.submit(paymentFormSubmit);

        function paymentFormSubmit(e) {
            e.preventDefault();
            $form.find('button').prop('disabled', true);
            securionPay.createToken(components)
                .then(tokenCreatedCallback)
                .catch(errorCallback);
        }

        function errorCallback(error) {
            $('#payment-error').text(error.message);
            $form.find('button').prop('disabled', false);
        }

        function tokenCreatedCallback(token) {
            $form.append($('<input type="hidden" name="token" />').val(token.id));
            $form.unbind();
            $form.submit();
        }
    </script>
@endsection
