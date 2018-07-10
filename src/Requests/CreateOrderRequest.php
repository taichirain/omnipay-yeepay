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
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $request      = $this->httpClient->post($this->endpoint)->setBody($data);
        $response     = $request->send()->getBody();
        $responseData = json_decode($response);

        return $this->response = new CreateOrderResponse($this, $responseData);
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
