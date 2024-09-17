<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Request;

class BTCBlockChainController extends Controller
{
    /**
     * @var mixed
     */
    public $xpub;
    /**
     * @var mixed
     */
    public $api;
    public function __construct()
    {
        $payment = PaymentMethod::find(3);
        $this->api = $payment->val1;
        $this->xpub = $payment->val2;
    }

    /**
     * @param $log
     */
    public function process($log)
    {
        $message = 'Got the payment address';
        $status = true;
        if ($log && $log->btc_acc != null) {
            $address = $log->btc_acc;
        } else {
            $callback_url = urlencode(route('btc-ipn', ['invoice_id' => $log->order_number, 'passphrase' => 'SoftwarezonMaster852$%$%']));
            $btcRoot = "https://api.blockchain.info/v2/receive?xpub={$this->xpub}&callback={$callback_url}&key={$this->api}&gap_limit=20";
            $result = customGetCURL($btcRoot);
            $response = json_decode($result);
            $responseKey = key($response);
            if ($responseKey == 'message') {
                $status = false;
                $message = $response->message;
                $address = '';
            } else {
                $address = $response->address;
            }
        }
        $amount = convertUSDToCrypto('bitcoin', $log->usd);
        $qrCode = paymentQRCode($address, $amount);

        return [
            'success' => $status,
            'message' => $message,
            'address' => $address,
            'amount'  => $amount,
            'qrCode'  => $qrCode,
        ];
    }

    /**
     * @param Request $request
     */
    public function btcIPN(Request $request)
    {
        $custom = $_GET['invoice_id'];
        $passphrase = $_GET['passphrase'];
        $address = $_GET['address'];
        $value = $_GET['value'];
        $confirmations = $_GET['confirmations'];
        $value_in_btc = $_GET['value'] / 100000000;
        $trx_hash = $_GET['transaction_hash'];
        $data = PaymentLog::whereOrderNumber($custom)->wherePayment_id(3)->whereStatus(2)->firstOrFail();

        if ($data->status == 2) {
            if ($data->btc_amo == $value_in_btc && $data->btc_acc == $address && $passphrase == "SoftwarezonMaster852$%$%" && $confirmations > 2) {

                (new PaymentAction($data))->perform();

                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');

                return redirect()->route('user-dashboard');
            }
        }
    }
}
