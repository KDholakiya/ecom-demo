<?php

namespace Webkul\Cashfree\Payment;

use Illuminate\Support\Facades\Config;
use Webkul\Payment\Payment\Payment;

abstract class Cashfree extends Payment
{

    public function getCashfreeUrl($params = [])
    {
       return $this->getConfigData('sandbox') ? "https://test.cashfree.com/billpay/checkout/post/submit" : "https://www.cashfree.com/checkout/post/submit";
    }

}