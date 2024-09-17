<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="text-center">
            <h3>Current Balance : {{ Auth::user()->balance }} {{ $basic->currency }}</h3>
            <h3>Plan Charge : {{ $log->amount }} {{ $basic->currency }}</h3>
            @if ($log->amount <= Auth::user()->balance)
                <h3>Available Balance : {{ Auth::user()->balance - $log->amount }} {{ $basic->currency }}</h3>
                <hr>
                <form action="{{ route('commission-ipn') }}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="custom" value="{{ $log->order_number }}">
                    <button class="btn btn-primary btn-lg bg-softwarezon-x border-0 btn-block"><i class="fa fa-send"></i> Pay Now</button>
                </form>
            @else
                <h3>Current Balance Smaller then Plan Price.</h3>
                <hr>
                <button disabled class="btn btn-danger btn-lg bg-softwarezon-x border-0 btn-block"><i class="fa fa-times"></i> Insufficient Balance</button>
            @endif
        </div>
    </div>
</div>
