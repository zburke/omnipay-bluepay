<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay Auth Request.
 */
class AuthRequest extends AbstractRequest
{
    protected $action = 'AUTH';

    public function getData()
    {

        $data = $this->getBaseData();
	if ($this->getCardReference()) 
	{
            $data['RRNO'] = $this->getCardReference();
	} 
	elseif ($this->getCard()) 
	{
            $this->getCard()->validate();
            $data['PAYMENT_ACCOUNT'] = $this->getCard()->getNumber();
            $data['CARD_EXPIRE'] = $this->getCard()->getExpiryDate('my');
            $data['CARD_CVV2'] = $this->getCard()->getCvv();
            // $data>TxnId = $this->getTransactionId();
	} 
	else 
	{
            // either cardReference or card is required
            $this->validate('card');
        }
        return array_merge($data, $this->getBillingData());
    }
}
