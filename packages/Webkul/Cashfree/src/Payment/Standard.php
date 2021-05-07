<?php

namespace Webkul\Cashfree\Payment;


class Standard extends Cashfree
{

    protected $code  = 'cashfree';

    public function getRedirectUrl()
    {
        return route('cashfree.standard.redirect');
    }

    public function getFormFields()
    {
        $cart = app("cart");
        $cartData = $cart->prepareDataForOrder();
        $fields = [
            "appId" => $this->getConfigData('appId'),
            "orderId" =>  $cartData["cart_id"],
            "orderAmount" => $cartData["sub_total"],
            "orderCurrency" => $cartData["order_currency_code"],
            "orderNote" => "getvastra order",
            "customerName" => $cartData["customer_first_name"],
            "customerPhone" => $cartData["billing_address"]["phone"],
            "customerEmail" => $cartData["customer_email"],
            "returnUrl" => route('cashfree.standard.return'),
            "notifyUrl" => route('cashfree.standard.notify'),
        ];
        $fields['signature'] = $this->generateSignature($fields);
        return $fields;
    }

    public function generateSignature($postData){
        $secretKey = $this->getConfigData('secretKey');
        ksort($postData);
        $signatureData = "";
        foreach ($postData as $key => $value){
            $signatureData .= $key.$value;
        }
        $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
        $signature = base64_encode($signature);
        return $signature;
    }

    
    public function verifySignature($data){
        $secretKey = $this->getConfigData('secretKey');
        $signatureData = "";
        $signature = $data["signature"];
        unset($data['signature']);
        foreach ($data as $key => $value){
            $signatureData .= $value;
        }
        $hash_hmac = hash_hmac('sha256', $signatureData, $secretKey, true);
        $computedSignature = base64_encode($hash_hmac);
        return $signature == $computedSignature;
    } 
}