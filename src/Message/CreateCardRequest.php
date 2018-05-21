<?php

/**
 * BluePay Create Credit Card Request.
 */
namespace Omnipay\BluePay\Message;

/**
 * BluePay Create Credit Card Request.
 *
 * We do this by running an authorization for $0.
 */
class CreateCardRequest extends AbstractRequest
{
    protected $action = 'AUTH';
  
    public function getData()
    {
        $data = $this->getBaseData();
        $this->getCard()->validate();
        $data['PAYMENT_ACCOUNT'] = $this->getCard()->getNumber();
        $data['CARD_EXPIRE'] = $this->getCard()->getExpiryDate('my');
        $data['CARD_CVV2'] = $this->getCard()->getCvv();
        $data = array_merge($data, $this->getBillingData());
        $data['AMOUNT'] = '0.00';
        return $data;
    }
}
