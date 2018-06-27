<?php

namespace Omnipay\BluePay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * BluePay Response
 */
class Response extends AbstractResponse
{
    public $raw = null;
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->raw = (string) $data;


        $this->data = array();
        if ($data && strlen($data)) {
            parse_str($data, $this->data);
        } else {
            throw new InvalidResponseException();
        }
    }


    public function isSuccessful()
    {
        return isset($this->data['STATUS']) && ('1' === $this->data['STATUS']);
    }


    public function getStatus()
    {
        return $this->valueFor('STATUS');
    }


    public function getAuthCode()
    {
        return $this->valueFor('AUTH_CODE');
    }

    public function getCardReference()
    {
        return $this->valueFor('TRANS_ID');
    }


    public function getMessage()
    {
        return $this->valueFor('MESSAGE');
    }


    public function getAvsCode()
    {
        return $this->valueFor('AVS');
    }


    public function getCvv2Code()
    {
        return $this->valueFor('CVV2');
    }


    public function getTransactionReference()
    {
        return $this->valueFor('TRANS_ID');
    }

    public function getNumberMasked()
    {
        return $this->valueFor('PAYMENT_ACCOUNT_MASK');
    }


    public function data()
    {
        return $this->data;
    }


    private function valueFor($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : '';
    }
}
