<?php

namespace OU;

use PHPUnit\Framework\TestCase;

class ClientIPAddressFinderTest extends TestCase
{
    protected $serverParams;

    public function setUp()
    {
        $this->serverParams = [
            'HTTP_X_FORWARDED_FOR' => '127.0.0.1',
            'HTTP_CLIENT_IP' => '127.0.0.2',
            'REMOTE_ADDR' => '127.0.0.3'
        ];
    }

    public function testHttpXForwarderFor()
    {
        $ip = ClientIPAddressFinder::find($this->serverParams);
        $this->assertEquals('127.0.0.1', $ip);
    }

    public function testWhenHttpXForwarderForEmpty()
    {
        $this->serverParams['HTTP_X_FORWARDED_FOR'] = '';
        $ip = ClientIPAddressFinder::find($this->serverParams);
        $this->assertEquals('127.0.0.2', $ip);
    }

    public function testWhenHttpXForwarderForNotFound()
    {
        unset($this->serverParams['HTTP_X_FORWARDED_FOR']);
        $ip = ClientIPAddressFinder::find($this->serverParams);
        $this->assertEquals('127.0.0.2', $ip);
    }

    public function testWhenHttpClientIPEmpty()
    {
        unset($this->serverParams['HTTP_X_FORWARDED_FOR']);
        $this->serverParams['HTTP_CLIENT_IP'] = '';
        $ip = ClientIPAddressFinder::find($this->serverParams);
        $this->assertEquals('127.0.0.3', $ip);
    }

    public function testWhenHttpClientIPNotFound()
    {
        unset($this->serverParams['HTTP_X_FORWARDED_FOR']);
        unset($this->serverParams['HTTP_CLIENT_IP']);
        $ip = ClientIPAddressFinder::find($this->serverParams);
        $this->assertEquals('127.0.0.3', $ip);
    }

    public function testWhenRemoteAddrNotFound()
    {
        unset($this->serverParams['HTTP_X_FORWARDED_FOR']);
        unset($this->serverParams['HTTP_CLIENT_IP']);
        unset($this->serverParams['REMOTE_ADDR']);
        $ip = ClientIPAddressFinder::find($this->serverParams);
        $this->assertEmpty($ip);
    }
}
