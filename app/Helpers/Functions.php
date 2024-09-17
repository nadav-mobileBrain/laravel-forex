<?php

/**
 * @param $value
 * @param $length
 * @param $starter
 * @return mixed
 */
function custom($value, $length = 5, $starter = 1)
{
    return $starter . str_pad($value, $length, '0', STR_PAD_LEFT);
}

/**
 * @param array $numbers
 */
function getCommaSeparatedNumbers(array $numbers)
{
    return implode(',', $numbers);
}

/**
 * @param $amount
 * @param $length
 */
function customAmountFormat($amount, $length = 2)
{
    return number_format((float) $amount, $length, '.', '');
}

function to_back()
{
    return redirect()->back();
}

/**
 * @param $message
 */
function toastMessage($type, $message)
{
    session()->flash('message', $message);
    session()->flash('type', $type);
}

/**
 * @param $url
 * @return mixed
 */
function customGetCURL($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

/**
 * @param $url
 * @return mixed
 */
function customGetCURLHeader($url, $headers)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

/**
 * @param $address
 * @param $amount
 * @param $currency
 */
function paymentQRCode($address, $amount, $currency = 'bitcoin')
{
    $var = "{$currency}:{$address}?amount={$amount}";
    return "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$var&choe=UTF-8\" title='' style='width:300px;' />";
}

/**
 * @param $crypto
 */
function convertUSDToCrypto($crypto, $usd)
{
    if (strtolower($crypto) == 'bitcoin' || strtolower($crypto) == 'btc') {
        $api = "https://blockchain.info/tobtc?currency=USD&value=" . $usd;
        $cryptoAmount = customGetCURL($api);
        return round($cryptoAmount, 8);
    } else {
        $url = 'https://api.coinbase.com/v2/exchange-rates?currency=' . $crypto;
        $response = customGetCURL($url);
        $arrayData = json_decode($response, true);
        $value = $arrayData['data']['rates']['USD'];
        return round($usd / $value, 8);
    }
}

/**
 * @param $url
 * @param $fields
 * @param $headers
 * @return mixed
 */
function customPostCURLHeader($url, $fields, $headers)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

/**
 * @param $url
 * @param array $data
 * @return mixed
 */
function customPostCURL($url, $data = [])
{
    $fields = http_build_query($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}
