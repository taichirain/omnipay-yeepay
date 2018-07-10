<?php

namespace Omnipay\Yeepay\Responses;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class BaseAbstractResponse
 * @package Omnipay\WechatPay\Message
 */
abstract class BaseAbstractResponse extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        $data = $this->getData();

        return isset($data['code']) && $data['code'] != '1';
    }
}
