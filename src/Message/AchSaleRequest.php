<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay ACH Sale Request
 */
class AchSaleRequest extends AbstractRequest
{
    protected $action = 'SALE';

    public function getData()
    {
        return array_merge($this->getBaseData(), $this->getBillingData());
    }
}
