<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use BlockIo\Client;
use Exception;

class BlockIoController extends Controller
{
    /**
     * @var mixed
     */
    public $blockIo;
    /**
     * @var mixed
     */
    public $currency;

    public function __construct()
    {
        $method = PaymentMethod::find(1008);
        $version = 2;
        $this->blockIo = new Client($method->val1, $method->val2, $version);
    }

    /**
     * @return mixed
     */
    public function process($log, $currency)
    {
        try {
            if ($log) {
                if ($log->btc_acc) {
                    $address = $log->btc_acc;
                } else {
                    $response = $this->blockIo->get_new_address([
                        'label' => strtoupper($log->order_number),
                    ]);
                    $address = $response->data->address;
                }
            } else {
                $response = $this->blockIo->get_new_address([
                    'label' => strtoupper($log->order_number),
                ]);
                $address = $response->data->address;
            }

            if ($currency == 'Bitcoin') {
                $symbol = 'BTC';
            } elseif ($currency == 'Litecoin') {
                $symbol = 'LTC';
            } elseif ($currency == 'Dogecoin') {
                $symbol = 'XDG';
            }

            $payableAmount = convertUSDToCrypto($symbol, $log->usd);

            $qr = paymentQRCode($address, $payableAmount, strtolower($currency));

            $action = [
                'success'    => true,
                'btc_amount' => $payableAmount,
                'btc_wallet' => $address,
                'qr_code'    => $qr,
            ];
        } catch (Exception $e) {
            $action = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $action;
    }

    /**
     * @param Re $args
     */
    public function ipn()
    {
        $logs = PaymentLog::wherePaymentId(1008)->whereNotNull('btc_amo')->whereNotNull('btc_acc')->whereStatus(2)->get();
        foreach ($logs as $log) {
            $response = $this->blockIo->get_address_balance(['addresses' => $log->btc_acc]);
            if (($response->status == 'success') && ($response->data->available_balance >= $log->btc_amo)) {
                (new PaymentAction($log))->perform();
            }
        }
    }
}
