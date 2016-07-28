<?php

namespace Omnipay\BluePay;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected $voidOptions;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->purchaseOptions = array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        );

        $this->captureOptions = array(
            'amount' => '10.00',
            'transactionReference' => '12345',
        );

        $this->voidOptions = array(
            'transactionReference' => '12345',
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('AuthSuccess.txt');
        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('100307069144', $response->getTransactionReference());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('AuthFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Expiration date required for CREDIT', $response->getMessage());
    }


    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('100307069429', $response->getTransactionReference());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('CaptureFailure.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('COULD NOT LOCATE ORIGINAL TRANSACTION', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('SaleSuccess.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('100307070181', $response->getTransactionReference());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('SaleFailure.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('CARD ACCOUNT NOT VALID', $response->getMessage());
    }

    public function testAchPurchaseSuccess()
    {
        $this->setMockHttpResponse('AchSaleSuccess.txt');

        $response = $this->gateway->achPurchase($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('100346171008', $response->getTransactionReference());
    }

    public function testAchPurchaseFailure()
    {
        $this->setMockHttpResponse('AchSaleFailure.txt');

        $response = $this->gateway->achPurchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('SECURITY ERROR', $response->getMessage());
    }
}
