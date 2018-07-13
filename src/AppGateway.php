<?php

namespace Omnipay\Yeepay;

use Omnipay\Yeepay\BaseAbstractGateway;

class AppGateway extends BaseAbstractGateway
{

    public function getName()
    {
        return 'Yeepay App Gateway';
    }

    public function getTradeType()
    {
        return 'APP';
    }
    
}
