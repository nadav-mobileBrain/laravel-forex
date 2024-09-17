<div class="row">
    <div class="col-md-12">
        <h4 style="text-align: center;">
            {!! $qrCode !!}
        </h4>
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <td width="30%" class="text-right">{{ $log->paymentMethod->val3 }} Amount:</td>
                    <td>{{ $log->btc_amo }}</td>
                </tr>
                <tr>
                    <td width="30%" class="text-right">{{ $log->paymentMethod->val3 }} Address:</td>
                    <td>{{ $log->btc_acc }}</td>
                </tr>
            </tbody>
        </table>
        <h4 style="text-align: center;">
            <strong style="color: red;">Scan this QR Code or copy the address and send exactly the same amount.</strong>
        </h4>
    </div>
</div>
