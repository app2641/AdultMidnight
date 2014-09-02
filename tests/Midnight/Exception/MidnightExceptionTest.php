<?php


use Midnight\Exception\MidnightException;

class MidnightExceptionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    これはテストです
     * @group exception
     * @group exception-throw
     */
    public function throwのテスト ()
    {
        try {
            throw new MidnightException('これはテストです');
        
        } catch (MidnightException $e) {
            throw $e;
        }
    }


    /**
     * @test
     * @group exception
     * @group exception-get-trace
     **/
    public function エラー内容をトレースする ()
    {
        try {
            throw new MidnightException('これはテストです');
        
        } catch (MidnightException $e) {
            $trace = $e->getErrorTrace();
            $this->assertTrue(is_string($trace));
        }
    }
}
