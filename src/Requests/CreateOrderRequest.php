<?php

namespace Omnipay\Yeepay\Requests;

use Omnipay\Yeepay\Common\Signer;
use Omnipay\Yeepay\Responses\CreateOrderResponse;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * 统一下单
 * @package Omnipay\Yeepay\Requests
 */
abstract class CreateOrderRequest extends BaseAbstractRequest
{
    protected $method = '';
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

        // $data = $this->parameters->all();

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

        $data = array_merge($hmacdata, $extra_data);

        $data['hmac'] = Signer::signHmac($hmacdata, $this->getKeyValue());

        $json = Signer::cn_json_encode($data);

        $aesStr = Signer::signAes($json, $this->getKeyAesValue());

        $requestData = [
            'customernumber' => $this->getCustomerNumber(),
            'data' => $aesStr
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

    public function getAmount() 
    {
        return $this->getParameter('amount');
    }

    public function setAmount() 
    {
        $this->setParameter('amount', $amount);
    }

    public function getAssure() 
    {
        return $this->getParameter('assure');
    }

    public function setAssure() 
    {
        $this->setParameter('assure', $assure);
    }

    public function getProductCat()
    {
        $this->setParameter('ip', $ip);
    }

    public function setProductCat() 
    {
        $this->setParameter('productcat', $productcat);
    }

    public function getProductDesc() 
    {
        return $this->getParameter('productdesc');
    }

    public function setProductDesc() 
    {
        $this->setParameter('productdesc', $productdesc);
    }

    public function getDivideInfo() 
    {
        return $this->getParameter('divideinfo');
    }

    public function setDivideInfo() 
    {
        $this->setParameter('ip', $ip);
    }

    public function getWebCallbackUrl() 
    {
        return $this->getParameter('webcallbackurl');
    } 

    public function setWebCallbackUrl() 
    {
        $this->setParameter('webcallbackurl', $webCallbackUrl);
    } 

    public function getBankId() 
    {
        return $this->getParameter('bankid');
    }

    public function setBankId() 
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

    public function setMemo() 
    {
        $this->setParameter('memo', $memo);
    }

    public function getUserNo() 
    {
        return $this->getParameter('userno');
    }

    public function setUserNo() 
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

        // 2.x 的写法
        // $request      = $this->httpClient->post($this->endpoint)->setBody($data);
        // $response     = $request->send()->getBody();
        // $responseData = json_decode($response);

        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), [], http_build_query($data, '', '&'));

        return $this->createResponse($httpResponse->getBody()->getContents());

        // return $this->response = new CreateOrderResponse($this, $responseData);
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
