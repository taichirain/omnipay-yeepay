<?php

namespace Omnipay\Yeepay\Requests;

use Omnipay\Yeepay\Common\Signer;
use Omnipay\Yeepay\Responses\YeepayCompletePurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

class YeepayCompletePurchaseRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $data = $this->parameters->all();
        $data['sign_type'] = $this->getSignType();
        $data['sign'] = $this->sign($data, $this->getSignType());
        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new YeepayCompletePurchaseResponse($this, $data);
    }

    public function getOrderSerialNumber()
    {
        return $this->getParameter('order_serial_number');
    }

    public function setOrderSerialNumber($value)
    {
        return $this->setParameter('order_serial_number', $value);
    }

    public function getSubject()
    {
        return $this->getParameter('subject');
    }

    public function setSubject($value)
    {
        return $this->setParameter('subject', $value);
    }

    public function getTotalFee()
    {
        return $this->getParameter('total_fee');
    }

    public function setTotalFee($value)
    {
        return $this->setParameter('total_fee', $value);
    }

    public function getStatus()
    {
        return $this->getParameter('status');
    }

    public function setStatus($value)
    {
        return $this->setParameter('status', $value);
    }

    public function getSignType()
    {
        return $this->sign_type;
    }

    public function setSignType($value)
    {
        $this->sign_type = $value;
        return $this;
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function setSign($value)
    {
        $this->sign = $value;
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($value)
    {
        $this->key = $value;
        return $this;
    }
    
    private function sign($params, $signType)
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
