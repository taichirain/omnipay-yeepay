<?php

namespace Omnipay\Yeepay\Requests;

use Omnipay\Yeepay\Common\Signer;
use Omnipay\Yeepay\Request\YeepayPurchaseRequest;
use Omnipay\Yeepay\Responses\YeepayPurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class YeepayWebPurchaseRequest
 * @package Omnipay\Yeepay\Requests
 */
class YeepayWebPurchaseRequest extends YeepayPurchaseRequest
{

    public function getData()
    {
        $this->validateParams();
        $data = $this->parameters->all();
        $data['sign_type'] = $this->getSignType();
        $data['sign'] = $this->sign($data, $this->getSignType());
        return $data;
    }

    protected function validateParams()
    {
        $this->validate(
            'order_serial_number',
            'subject',
            'total_fee',
            'return_url',
            'notify_url'
        );
    }

    public function sendData($data)
    {
        return $this->response = new YeepayPurchaseResponse($this, $data);
    }

    protected function sign($params, $signType)
    {
        $signer = new Signer($params);
        $signType = strtoupper($signType);
        if ($signType == 'MD5') {
            if (! $this->getKey()) {
                throw new InvalidRequestException('The `key` is required for `MD5` sign_type');
            }
            $sign = $signer->signWithMD5($this->getKey());
        } else {
            throw new InvalidRequestException('The signType is not allowed');
        }
        return $sign;
    }
}
