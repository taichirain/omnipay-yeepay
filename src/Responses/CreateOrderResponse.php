<?php

namespace Omnipay\Yeepay\Responses;

use Omnipay\Yeepay\Helper;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class CreateOrderResponse extends BaseAbstractResponse
{
    /**
     * @var YeepayPurchaseRequest
     */
    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        // parse_str($data, $this->data);
        $this->data = json_decode($data,true);
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->request->getEndpoint() . 'unifiedorder/?' . http_build_query($this->getRedirectData());
    }

    public function getTransactionReference()
    {
        if ($this->isSuccessful()) {
            $data = [
                // 'customernumber' => $this->request->getCustomerNumber(),
                'requestid' => $this->request->getRequestId(),
                'return_code'  => $this->getCode(),
                'externalid'   => $this->getExternalId(),
                'orderid'   => $this->getOrderId(),
                'amount'   => $this->getAmount(),
                'payurl' => $this->getPayUrl(),
                'hmac' => $this->getHmac(),
            ];
        } else {
            $data['return_code'] = $this->getCode();
            $data['return_message'] = $this->getMsg();
        }

        return $data;
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        return $this->data;
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }
}
