<?php

namespace Omnipay\Yeepay\Requests;

use Omnipay\Yeepay\Common\Signer;
use Omnipay\Yeepay\Responses\CreateOrderResponse;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * 统一下单
 * @package Omnipay\Yeepay\Requests
 */
abstract class CreateOrderRequest extends BaseAbstractRequest
{

    protected $endpoint = 'https://o2o.yeepay.com/zgt-api/api/pay';

    public function getData()
    {
        $this->validateParams();

        $payProductType = strtoupper($this->getPayProductType());
        if(in_array($payProductType,['WECHATU','WECHATG','APP_WX','APP_ZFB']))
        {
            $this->validate('ip');
        }
        if($payProductType == 'WECHATG')
        {
            $this->validate('userno');
        }

        //needRequestHmac
        $data = [
            'requestid'      => $this->getRequestId(), 
            'amount'         => $this->getAmount(), 
            'assure'         => $this->getAssure(), 
            'productname'    => $this->getProductName(), 
            'productcat'     => $this->getProductCat(), 
            'productdesc'    => $this->getProductDesc(), 
            'divideinfo'     => $this->getDivideInfo(), 
            'callbackurl'    => $this->getCallbackUrl(), 
            'webcallbackurl' => $this->getWebCallbackUrl(), 
            'bankid'         => $this->getBankId(), 
            'period'         => $this->getPeriod(), 
            'memo'           => $this->getMemo() 
        ];

        $data = $this->parameters->all();
        $data['sign'] = $this->sign($data, $this->getSignType());

        $extra_data = [
            'payproducttype' => $this->getPayproducttype(), 
            'userno'         => $this->getUserNo(), 
            'ip'             => $this->getIp(), 
            'cardname'       => $this->getCardName(), 
            'idcard'         => $this->getIdCard(), 
            'bankcardnum'    => $this->getBankCardNum(),
            'mobilephone'    => $this->getMobilePhone(),
            'orderexpdate'   => $this->getOrderExpdate(), 
        ];
        return $data;
    }

    protected function validateParams()
    {
        //must fill, but not found
        $this->validate(
            'requestid', 'amount', 'callbackurl'
        );
    }

    public function sendData($data)
    {
        return $this->response = new YeepayPurchaseResponse($this, $data);
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

    public function getReturnUrl()
    {
        return $this->getParameter('return_url');
    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('return_url', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }

    public function setNotifyUrl($value)
    {
        return $this->setParameter('notify_url', $value);
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

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($value)
    {
        $this->key = $value;
        return $this;
    }
    
    public function getEndpoint()
    {
        return $this->endpoint;
    }
    
    public function setEndpoint($value)
    {
        $this->endpoint = $value;
        return $this;
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
