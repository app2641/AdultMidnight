<?php


use Midnight\Utility\Tracer;
use Midnight\Aws\SES;

class TracerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @group tracer
     * @group tracer-get-log
     **/
    public function エラー内容をトレースする ()
    {
        try {
            throw new \Exception('これはテストです');
        
        } catch (\Exception $e) {
            $log = Tracer::getLog($e);
            $this->assertTrue(is_string($log));
        }
    }
}
