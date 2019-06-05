<?php

namespace Omnipay\Yeepay\Responses;

use Omnipay\Yeepay\Helper;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class TransferQueryResponse extends BaseAbstractResponse
{
    /**
     * @var YeepayPurchaseRequest
     */
    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = json_decode($data,true);
    }

    public function isRedirect()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        //
    }

    public function getTransactionReference()
    {

        if ($this->isSuccessful()) {
            $data = [
                'requestid' => $this->request->getRequestId(),
                'status'   => $this->request->getStatus(),
                'message' => $this->request->getMessage(),
                'ledgerno' => $this->request->getLedgerno(),
                'amount' => $this->getAmount(),
                'return_code' => $this->getCode(),
                'return_message' => $this->getMsg(),
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
