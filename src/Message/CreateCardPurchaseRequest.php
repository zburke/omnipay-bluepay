<?php

/**
 * BluePay Create Credit Card Request.
 */
namespace Omnipay\BluePay\Message;

/**
 * BluePay Create Credit Card Request.
 *
 * Similar to ordinary sale, except we can't use a card reference.
 */
class CreateCardPurchaseRequest extends AbstractRequest
{
    protected $action = 'SALE';
  
    public function getData()
    {
        $data = $this->getBaseData();
        $this->getCard()->validate();
        $data['PAYMENT_ACCOUNT'] = $this->getCard()->getNumber();
        $data['CARD_EXPIRE'] = $this->getCard()->getExpiryDate('my');
        $data['CARD_CVV2'] = $this->getCard()->getCvv();
        $data = array_merge($data, $this->getBillingData());
        return $data;
    }
}
