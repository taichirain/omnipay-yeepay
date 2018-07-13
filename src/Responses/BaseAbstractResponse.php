<?php

namespace Omnipay\Yeepay\Responses;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class BaseAbstractResponse
 * @package Omnipay\WechatPay\Message
 */
abstract class BaseAbstractResponse extends AbstractResponse
{

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        $data = $this->getData();

        return isset($data['code']) && $data['code'] == '1';
    }

    /**
     * pay result code
     *
     * @return boolean
     */
    public function getCode()
    {
        $data = $this->data;
        return isset($data['code']) ? $data['code'] : '';
    }

    /**
     * yeepay order id
     *
     * @return boolean
     */
    public function getExternalId()
    {
        $data = $this->data;
        return isset($data['externalid']) ? $data['externalid'] : '';
    }

    /**
     * order amount
     *
     * @return boolean
     */
    public function getAmount()
    {
        $data = $this->data;
        return isset($data['amount']) ? $data['amount'] : '';
    }

    /**
     * redirect pay url
     *
     * @return boolean
     */
    public function getPayUrl()
    {
        $data = $this->data;
        return isset($data['payurl']) ? $data['payurl'] : '';
    }

    /**
     * hmac
     *
     * @return boolean
     */
    public function getHmac()
    {
        $data = $this->data;
        return isset($data['hmac']) ? $data['hmac'] : '';
    }
}
