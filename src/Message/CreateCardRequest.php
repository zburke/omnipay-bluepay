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
class CreateCardRequest extends AuthRequest
{
    public function getData()
    {
        $data = parent::getData();
        $data['AMOUNT'] = '0.00';
        return $data;
    }
}
