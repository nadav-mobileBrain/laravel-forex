<div class="row">
    <div class="col-md-12">
        <form action="{{ route('rozorpay-submit') }}" method="post">
            @csrf
            <input type="hidden" name="custom" value="{{ $log->order_number }}">
            <script src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="{{ $log->paymentMethod->val1 }}"
                    data-amount="{{ $log->usd * 100 }}"
                    data-currency="{{ $log->paymentMethod->currency }}"
                    data-buttontext="Pay via {{ $log->paymentMethod->name }}"
                    data-name="{{ $site_title }}"
                    data-description="{{ $log->plan->name }} - Plan Subscription Payment"
                    data-image="{{ asset('assets/images/logo.png') }}"
                    data-prefill.name="{{ $log->user->name }}"
                    data-prefill.email="{{ $log->user->email }}"
                    data-theme.color="#ff7529"></script>
        </form>
    </div>
</div>
@section('scripts')
    <script>
        $(document).ready(function() {
            $('input[type="submit"]').addClass("btn btn-primary btn-lg btn-block");
        })
    </script>
@endsection
