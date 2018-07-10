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

    /**
      * @取得hmac签名
      * @$dataArray 明文数组或者字符串
      * @$key 密钥
      * @return string
      *
     */
    function getHmac(array $dataArray, $key) {
        
        if ( !isViaArray($dataArray) ) {
        
            return null;    
        }
        
        if ( !$key || empty($key) ) {
            
            return null;
        }
        
        if ( is_array($dataArray) ) {
        
            $data = implode("", $dataArray);
        } else {
        
            $data = strval($dataArray); 
        }
        

        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            
            $key = pack("H*",md5($key));
        }
        
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack("H*",md5($k_ipad . $data)));
    }

    /**
      * @取得aes加密
      * @$dataArray 明文字符串
      * @$key 密钥
      * @return string
      *
     */
    function getAes($data, $aesKey) {

        $aes = new CryptAES();
        $aes->set_key($aesKey);
        $aes->require_pkcs5();
        $encrypted = strtoupper($aes->encrypt($data));
        
        return $encrypted;

    }

    /**
      * @取得aes解密
      * @$dataArray 密文字符串
      * @$key 密钥
      * @return string
      *
     */
    function getDeAes($data, $aesKey) {

        $aes = new CryptAES();
        $aes->set_key($aesKey);
        $aes->require_pkcs5();
        $text = $aes->decrypt($data);
        
        return $text;
    }




















}
