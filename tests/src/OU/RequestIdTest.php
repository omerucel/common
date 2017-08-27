<?php

namespace OU;

use PHPUnit\Framework\TestCase;

class RequestIdTest extends TestCase
{
    public function testUnique()
    {
        $id1 = new RequestId();
        $id2 = new RequestId();
        $this->assertNotEquals($id1->__toString(), $id2->__toString());
    }

    public function testDefaultValue()
    {
        $id1 = new RequestId('test');
        $this->assertEquals('test', $id1->__toString());
    }

    public function testReset()
    {
        $id1 = new RequestId('test1');
        $id1->reset('test2');
        $this->assertEquals('test2', $id1->__toString());
    }
}
