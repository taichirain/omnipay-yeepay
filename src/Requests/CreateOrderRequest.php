<?php

namespace Omnipay\Yeepay\Requests;

use Omnipay\Yeepay\Helper;
use Omnipay\Yeepay\Common\Signer;
use Omnipay\Yeepay\Common\CryptAES;
use Omnipay\Yeepay\Responses\CreateOrderResponse;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * 统一下单
 * @package Omnipay\Yeepay\Requests
 */
class CreateOrderRequest extends BaseAbstractRequest
{
    protected $method = '';
    protected $endpoint = 'https://o2o.yeepay.com/zgt-api/api/pay';
    // protected $endpoint = 'http://d.yeepay.com/php/test.php';

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

        if($payProductType == 'WECHATAPP')
        {
            $this->validate('platform','appname','appstatement');
        }

        //needRequestHmac
        $hmacdata = [
            'customernumber' => $this->getCustomerNumber(),
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

        $extra_data = [
            'payproducttype' => $this->getPayproducttype(), 
            'userno'         => $this->getUserNo(), 
            'ip'             => $this->getIp(), 
            'cardname'       => $this->getCardName(), 
            'idcard'         => $this->getIdCard(), 
            'bankcardnum'    => $this->getBankCardNum(),
            'mobilephone'    => $this->getMobilePhone(),
            'orderexpdate'   => $this->getOrderExpdate(),
            'platform'       => $this->getPlatform(),
            'appname'       => $this->getAppName(),
            'appstatement'   => $this->getAppStatement(),
            'directcode'     => $this->getDirectcode()
        ];

        $data = array_merge($hmacdata, $extra_data);
        
        $data['hmac'] = Signer::signHmac($hmacdata, $this->getKeyValue());

        $json = Signer::cn_json_encode($data);
        
        $aes = new CryptAES($this->getKeyAesValue());
        $encrypted = strtoupper($aes->encrypt_openssl($json));
        // $unencrypted = $aes->decrypt_openssl('49991B21F8AF8DAA7E6DCE4A4F589D9299C0C2F26791CE394C43684DC76111D7EA2F7240115B746516AF1A9498C47FF349A7194A41C1C72F0D131199AFBDF019');
        $requestData = [
            'customernumber' => $this->getCustomerNumber(),
            'data' => $encrypted
        ];

        return $requestData;
    }

    protected function validateParams()
    {
        //must fill, but not found
        $this->validate(
            'requestid', 'amount', 'callbackurl'
        );
    }

    public function getAssure() 
    {
        return $this->getParameter('assure');
    }

    public function setAssure($assure) 
    {
        $this->setParameter('assure', $assure);
    }

    public function getProductCat()
    {
        $this->getParameter('productcat');
    }

    public function setProductCat($productcat) 
    {
        $this->setParameter('productcat', $productcat);
    }

    public function getProductDesc() 
    {
        return $this->getParameter('productdesc');
    }

    public function setProductDesc($productdesc) 
    {
        $this->setParameter('productdesc', $productdesc);
    }

    public function getDivideInfo() 
    {
        return $this->getParameter('divideinfo');
    }

    public function setDivideInfo($divideinfo) 
    {
        $this->setParameter('divideinfo', $divideinfo);
    }

    public function getWebCallbackUrl() 
    {
        return $this->getParameter('webcallbackurl');
    } 

    public function setWebCallbackUrl($webCallbackUrl) 
    {
        $this->setParameter('webcallbackurl', $webCallbackUrl);
    } 

    public function getBankId() 
    {
        return $this->getParameter('bankid');
    }

    public function setBankId($bankid) 
    {
        $this->setParameter('bankid', $bankid);
    }

    public function getPeriod() 
    {
        return $this->getParameter('period');
    }

    public function setPeriod($period) 
    {
        $this->setParameter('period', $period);
    }

    public function getMemo() 
    {
        return $this->getParameter('memo');
    }

    public function setMemo($memo) 
    {
        $this->setParameter('memo', $memo);
    }

    public function getUserNo() 
    {
        return $this->getParameter('userno');
    }

    public function setUserNo($userNo) 
    {
        $this->setParameter('userno', $userNo);
    }

    public function getCardName() 
    {
        return $this->getParameter('cardname');
    }

    public function setCardName($cardName) 
    {
        $this->setParameter('cardname', $cardName);
    }

    public function getIdCard() 
    {
        return $this->getParameter('idcard');
    }

    public function setIdCard($idCard) 
    {
        $this->setParameter('idcard', $idCard);
    }

    public function getBankCardNum() 
    {
        return $this->getParameter('bankcardnum');
    }

    public function setBankCardNum($bankCardNum) 
    {
        $this->setParameter('bankcardnum', $bankCardNum);
    }

    public function getMobilePhone() 
    {
        return $this->getParameter('mobilephone');
    }

    public function setMobilePhone($mobilePhone) 
    {
        $this->setParameter('mobilephone', $mobilePhone);
    }

    public function getOrderExpdate() 
    {
        return $this->getParameter('orderexpdate');
    }

    public function setOrderExpdate($orderExpdate) 
    {
        $this->setParameter('orderexpdate', $orderExpdate);
    }

    public function getPlatform() 
    {
        return $this->getParameter('platform');
    }

    public function setPlatform($platform) 
    {
        $this->setParameter('platform', $platform);
    }

    public function getAppName() 
    {
        return $this->getParameter('appname');
    }

    public function setAppName($appName) 
    {
        $this->setParameter('appname', $appName);
    }

    public function getAppStatement() 
    {
        return $this->getParameter('appstatement');
    }

    public function setAppStatement($appStatement) 
    {
        $this->setParameter('appstatement', $appStatement);
    }

    public function getDirectcode() 
    {
        return $this->getParameter('directcode');
    }

    public function setDirectcode($directcode) 
    {
        $this->setParameter('directcode', $directcode);
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
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

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        // Guzzle HTTP Client createRequest does funny things when a GET request
        // has attached data, so don't send the data if the method is GET.
        
        // if ($this->getHttpMethod() == 'GET') {
        //     $requestUrl = $this->getEndpoint() . '?' . http_build_query($data);
        //     $body = null;
        // } else {
        //     $body = $this->toJSON($data);
        //     $requestUrl = $this->getEndpoint();
        // }

        // Might be useful to have some debug code here, PayPal especially can be
        // a bit fussy about data formats and ordering.  Perhaps hook to whatever
        // logging engine is being used.
        // echo "Data == " . json_encode($data) . "\n";

        // try {
        //     $httpResponse = $this->httpClient->request(
        //         $this->getHttpMethod(),
        //         $this->getEndpoint(),
        //         [],
        //         $body
        //     );
        //     // Empty response body should be parsed also as and empty array
        //     $body = (string) $httpResponse->getBody()->getContents();
        //     $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
        //     return $this->response = $this->createResponse($jsonToArrayResponse, $httpResponse->getStatusCode());
        // } catch (\Exception $e) {
        //     throw new InvalidResponseException(
        //         'Error communicating with payment gateway: ' . $e->getMessage(),
        //         $e->getCode()
        //     );
        // }

        $fieldData = http_build_query($data, '', '&');

        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], $fieldData);

        $unencrypted = [];
        $body = $httpResponse->getBody()->getContents();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
        if($jsonToArrayResponse) {
            $cryptAes = new CryptAES($this->getKeyAesValue());
            $unencrypted = $cryptAes->decrypt_openssl($jsonToArrayResponse['data']);
        }

        return $this->createResponse($unencrypted, $httpResponse->getStatusCode());
    }

    /**
     * Returns object JSON representation required by PayPal.
     * The PayPal REST API requires the use of JSON_UNESCAPED_SLASHES.
     *
     * Adapted from the official PayPal REST API PHP SDK.
     *
     * @param int $options http://php.net/manual/en/json.constants.php
     * @return string
     */
    public function toJSON($data, $options = 0)
    {
        // Because of PHP Version 5.3, we cannot use JSON_UNESCAPED_SLASHES option
        // Instead we would use the str_replace command for now.
        // TODO: Replace this code with return json_encode($this->toArray(), $options | 64); once we support PHP >= 5.4
        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
            return json_encode($data, $options | 64);
        }
        return str_replace('\\/', '/', json_encode($data, $options));
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new CreateOrderResponse($this, $data, $statusCode);
    }
    
}
