<div class="row">
    <div class="col-md-12">
        <form action="{{ route('sslcommerz-submit') }}" method="post">
            @csrf
            <input type="hidden" name="custom" value="{{ $log->order_number }}" />
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-send"></i> Pay via {{ $log->paymentMethod->name }}</button>
        </form>
    </div>
</div>