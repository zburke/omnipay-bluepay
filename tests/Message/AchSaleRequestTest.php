<?php

namespace Omnipay\BluePay\Message;

use Omnipay\Tests\TestCase;

class AchSaleRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AchSaleRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'clientIp' => '10.0.0.1',
                'amount' => '11.00',
                'customerId' => 'cust-id',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('SALE', $data['TRANS_TYPE']);
    }
}
