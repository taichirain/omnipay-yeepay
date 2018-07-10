<?php

namespace Omnipay\Yeepay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Yeepay\Requests\YeepayCompletePurchaseRequest;

abstract class BaseAbstractGateway extends AbstractGateway
{

    /**
     * @return mixed
     */
    public function getCustomerNumber()
    {
        return $this->getParameter('customer_number');
    }

    /**
     * @param mixed $customerNumber
     */
    public function setCustomerNumber($customerNumber)
    {
        $this->setParameter('customer_number', $customerNumber);
    }


    /**
     * @return mixed
     */
    public function getCustomerKey()
    {
        return $this->getParameter('customer_key');
    }


    /**
     * @param mixed $customerKey
     */
    public function setCustomerKey($customerKey)
    {
        $this->setParameter('customer_key', $customerKey);
    }

    /**
     * @return mixed
     */
    public function getAesCustomerKey()
    {
        return $this->getParameter('aes_customer_key');
    }

    /**
     * @param mixed $aesCustomerKey
     */
    public function setAesCustomerKey($aesCustomerKey)
    {
        $this->setParameter('aes_customer_key', $aesCustomerKey);
    }


    /**
     * @return mixed
     */
    public function getPayProductType()
    {
        return $this->getParameter('pay_product_type');
    }

    /**
     * @param mixed $payProductType
     */
    public function setPayProductType($payProductType)
    {
        $this->setParameter('pay_product_type', $payProductType);
    }


    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->getParameter('product_name');
    }

    /**
     * @param mixed $productName
     */
    public function setProductName($productName)
    {
        $this->setParameter('product_name', $productName);
    }


    /**
     * @return mixed
     */
    public function getRequestid()
    {
        return $this->getParameter('requestid');
    }

    /**
     * @param mixed $requestid
     */
    public function setRequestid($requestid)
    {
        $this->setParameter('requestid', $requestid);
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
     * @param mixed $spbill_create_ip
     */
    public function setIp($ip)
    {
        $this->setParameter('ip', $ip);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\WechatPay\Message\CreateOrderRequest
     */
    public function unifiedorder($parameters = array())
    {
        return $this->createRequest('\Omnipay\Yeepay\Requests\CreateOrderRequest', $parameters);
    }
    
}
