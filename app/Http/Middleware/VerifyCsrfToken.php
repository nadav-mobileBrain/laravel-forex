<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * @var mixed
     */
    protected $addHttpCookie = true;
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'paypal-ipn',
        'perfect-ipn',
        'skrill-ipn',
        'coinpayment-ipn',
        'flutterwave-ipn',
        'mollie-ipn',
        'paystack-ipn',
        'paytm-ipn',
        'sslcommerz-success',
        'sslcommerz-cancel',
        'sslcommerz-fail',
        'sslcommerz-ipn',
        'coingate-ipn',
        'blockio-ipn',
        'cashmaal-ipn',
        'authorizenet-ipn',
        'alipayglobal-ipn',
    ];
}
