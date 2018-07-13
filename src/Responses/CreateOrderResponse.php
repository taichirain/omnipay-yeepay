<?php

namespace Omnipay\Yeepay\Responses;

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
        parse_str($data, $this->data);
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
                'customernumber' => $this->request->getCustomerNumber(),
                'requestid' => $this->request->getRequestId(),
                'code'  => $this->getCode(),
                'externalid'   => $this->getExternalId(),
                'amount'   => $this->getAmount(),
                'payurl' => $this->getPayUrl(),
                'hmac' => $this->getHmac(),
            ];

            $data['sign'] = Helper::sign($data, $this->request->getApiKey());
        } else {
            $data = null;
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
