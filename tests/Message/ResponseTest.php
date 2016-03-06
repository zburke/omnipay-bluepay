<?php

namespace Omnipay\BluePay\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response($this->getMockRequest(), '');
    }

    public function testAuthorizeSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('AuthSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('100307069144', $response->getTransactionReference());
    }

    public function testAuthorizeFailure()
    {
        $httpResponse = $this->getMockHttpResponse('AuthFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Expiration date required for CREDIT', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CaptureSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('100307069429', $response->getTransactionReference());
    }

    public function testCaptureFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CaptureFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('COULD NOT LOCATE ORIGINAL TRANSACTION', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('SaleSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('100307070181', $response->getTransactionReference());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('SaleFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('CARD ACCOUNT NOT VALID', $response->getMessage());
    }
}
