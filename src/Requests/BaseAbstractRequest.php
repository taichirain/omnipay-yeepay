<?php

namespace Omnipay\Yeepay\Requests;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Class BaseAbstractRequest
 * @package Omnipay\WechatPay\Message
 */
abstract class BaseAbstractRequest extends AbstractRequest
{
   /**
    * @return mixed
    */
    public function getCustomerNumber()
    {
        return $this->getParameter('customernumber');
    }

    /**
     * @param mixed $customerNumber
     */
    public function setCustomerNumber($customerNumber)
    {
        $this->setParameter('customernumber', $customerNumber);
    }


    /**
     * @return mixed
     */
    public function getKeyValue()
    {
        return $this->getParameter('keyValue');
    }


    /**
     * @param mixed $keyValue
     */
    public function setKeyValue($keyValue)
    {
        $this->setParameter('keyValue', $keyValue);
    }

    /**
     * @return mixed
     */
    public function getKeyAesValue()
    {
        return $this->getParameter('keyAesValue');
    }

    /**
     * @param mixed $keyAesValue
     */
    public function setKeyAesValue($keyAesValue)
    {
        $this->setParameter('keyAesValue', $keyAesValue);
    }

    /**
     * @return mixed
     */
    public function getPayProductType()
    {
        return $this->getParameter('payproducttype');
    }

    /**
     * @param mixed $payProductType
     */
    public function setPayProductType($payProductType)
    {
        $this->setParameter('payproducttype', $payProductType);
    }


    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->getParameter('productname');
    }

    /**
     * @param mixed $productName
     */
    public function setProductName($productName)
    {
        $this->setParameter('productname', $productName);
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->getParameter('requestid');
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId)
    {
        $this->setParameter('requestid', $requestId);
    }

    /**
     * @return mixed
     */
    public function getPlatform()
    {
        return $this->getParameter('platform');
    }

    /**
     * @param mixed $platform
     */
    public function setPlatform($platform)
    {
        $this->setParameter('platform', $platform);
    }

    /**
     * @return mixed
     */
    public function getAppstatement()
    {
        return $this->getParameter('appstatement');
    }

    /**
     * @param mixed $appstatement
     */
    public function setAppstatement($appstatement)
    {
        $this->setParameter('appstatement', $appstatement);
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->getParameter('ip');
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->setParameter('ip', $ip);
    }

    /**
     * @return mixed
     */
    public function getCallBackUrl()
    {
        return $this->getParameter('callbackurl');
    }

    /**
     * @param mixed $callBackUrl
     */
    public function setCallBackurl($callBackUrl)
    {
        $this->setParameter('callbackurl', $callBackUrl);
    }
    
}
