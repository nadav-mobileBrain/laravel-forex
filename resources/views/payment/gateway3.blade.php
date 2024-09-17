<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <td width="30%" class="text-right">BTC Amount:</td>
                    <td>{{ $log->btc_amo }} BTC</td>
                </tr>
                <tr>
                    <td width="30%" class="text-right">BTC Address:</td>
                    <td>{{ $log->btc_add }}</td>
                </tr>
            </tbody>
        </table>
        <h4 style="text-align: center;">
            {!! $qrCode !!} <br>
            <strong>SCAN TO SEND</strong> <br><br>
            <strong style="color: red;">NB: 3 Confirmation required to Credited your Account</strong>
        </h4>
    </div>
</div>
