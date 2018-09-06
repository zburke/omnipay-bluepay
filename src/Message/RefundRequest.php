<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay Refund Request
 */
class RefundRequest extends AbstractRequest
{
    protected $action = 'REFUND';

    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = $this->getBaseData();
        $data['MASTER_ID'] = $this->getParameter('transactionReference');

        return array_merge($data, $this->getBillingData());
    }
}
