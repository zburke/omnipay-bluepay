<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay Sale Request
 */
class SaleRequest extends AbstractRequest
{
    protected $action = 'SALE';

    public function getData()
    {
        $data = $this->getBaseData();

        if ($cardReference = $this->getCardReference()) {
            $data['MASTER_ID'] = $cardReference;
            // $data['CARD_EXPIRE'] = $this->getCard()->getExpiryDate('my');
        } elseif ($card = $this->getCard()) {
            $card->validate();
            $data['PAYMENT_ACCOUNT'] = $card->getNumber();
            $data['CARD_EXPIRE'] = $card->getExpiryDate('my');
            $data['CARD_CVV2'] = $card->getCvv();
        }

        return array_merge($data, $this->getBillingData());
    }
}
