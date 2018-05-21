<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://secure.bluepay.com/interfaces/bp20post';

    // there is no sandbox but you can send transactions with mode set to
    // "TEST" or "LIVE". calling $this->setDeveloperMode(true) sets the mode
    // to TEST.
    protected $developerEndpoint = 'https://secure.bluepay.com/interfaces/bp20post';

    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }


    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }


    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }


    public function getCustomId1()
    {
        return $this->getParameter('customId1');
    }

    public function setCustomId1($value)
    {
        return $this->setParameter('customId1', $value);
    }


    public function getCustomId2()
    {
        return $this->getParameter('customId2');
    }

    public function setCustomId2($value)
    {
        return $this->setParameter('customId2', $value);
    }


    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }


    public function getInvoiceId()
    {
        return $this->getParameter('invoiceId');
    }

    public function setInvoiceId($value)
    {
        return $this->setParameter('invoiceId', $value);
    }


    public function getMemo()
    {
        return $this->getParameter('memo');
    }

    public function setMemo($value)
    {
        return $this->setParameter('memo', $value);
    }

    public function getDeveloperMode()
    {
        return $this->getParameter('developerMode');
    }

    public function setDeveloperMode($value)
    {
        $this->setParameter('developerMode', $value);
    }

    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    protected function getBaseData()
    {
        $data = array(
            'ACCOUNT_ID' => $this->getAccountId(),
            'TRANS_TYPE' => $this->action,
            'MODE' => ($this->getDeveloperMode() || $this->getTestMode()) ? 'TEST' : 'LIVE',
            'MASTER_ID' => $this->getToken(),
        );
        return $data;
    }

    protected function getBillingData()
    {
        $data = array();
        $data['AMOUNT'] = $this->getAmount();

        if ($card = $this->getCard()) {
            $data['PAYMENT_ACCOUNT'] = $card->getNumber();
            $data['NAME1']   = $card->getBillingFirstName();
            $data['NAME2']   = $card->getBillingLastName();
            $data['ADDR1']   = $card->getBillingAddress1();
            $data['ADDR2']   = $card->getBillingAddress1();
            $data['CITY']    = $card->getBillingCity();
            $data['STATE']   = $card->getBillingState();
            $data['ZIP']     = $card->getBillingPostcode();
            $data['PHONE']   = $card->getBillingPhone();
            $data['EMAIL']   = $card->getEmail();
            $data['COUNTRY'] = $card->getBillingCountry();
        }

        $data['MEMO']        = $this->getMemo();
        $data['CUSTOM_ID']   = $this->getCustomId1();
        $data['CUSTOM_ID2']  = $this->getCustomId2();
        $data['ORDER_ID']    = $this->getOrderId();
        $data['INVOICE_ID']  = $this->getInvoiceId();
        $data['CUSTOMER_IP'] = $this->getClientIp();

        return $data;
    }


    /**
     * Create the required data array values for a "tamper proof seal"
     * by hashing a bunch of parameters that are part of the transaction.
     * Do this just before sending it.
     *
     * @return array
     */
    public function tps($data)
    {
        // A basic hash is always used.
        $hashstr = $this->getSecretKey() . $data['ACCOUNT_ID'];
        $hashstr .= $data['TRANS_TYPE'] . $data['AMOUNT'] . $data['MASTER_ID'];
        // Add in the first name and card data if I have them, that's what BluePay expects by default.
        if (!empty($data['NAME1']) && !empty($data['PAYMENT_ACCOUNT'])) {
            $hashstr .= $data['NAME1'].$data['PAYMENT_ACCOUNT'];
            $tps =  array('TAMPER_PROOF_SEAL' => md5($hashstr));
        } else { // Otherwise I need to tell BluePay what I'm using for the TPS via the TPS_DEF value.
            $tps =  array(
                'TAMPER_PROOF_SEAL' => md5($hashstr),
                'TPS_DEF' => "ACCOUNT_ID TRANS_TYPE AMOUNT MASTER_ID",
            );
        }
        return $tps;
    }


    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        // cribbed from https://github.com/thephpleague/omnipay-stripe/blob/master/src/Message/AbstractRequest.php
        // Fist add in my tamper-proof-seal
        $data = array_merge($data, $this->tps($data));
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();
        return $this->response = new Response($this, $httpResponse->getBody());
    }


    public function getEndpoint()
    {
        return $this->getDeveloperMode() ? $this->developerEndpoint : $this->liveEndpoint;
    }
}
