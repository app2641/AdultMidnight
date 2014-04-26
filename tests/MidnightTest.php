<?php

namespace Adult\Midnight;

class MidnightTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Midnight
     */
    protected $skeleton;

    protected function setUp()
    {
        $this->skeleton = new Midnight;
    }

    public function testNew()
    {
        $actual = $this->skeleton;
        $this->assertInstanceOf('\Adult\Midnight\Midnight', $actual);
    }

    /**
     * @expectedException \Adult\Midnight\Exception\LogicException
     */
    public function testException()
    {
        throw new Exception\LogicException;
    }
}
