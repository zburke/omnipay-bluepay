<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    protected $action = 'CAPTURE';

    public function getData()
    {
        $data = $this->getBaseData();
        $data['MASTER_ID'] = $this->getToken();

        return array_merge($data, $this->getBillingData());
    }
}
