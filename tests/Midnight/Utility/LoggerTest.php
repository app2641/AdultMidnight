<?php


use Midnight\Utility\Logger;
use Midnight\Aws\SES;

class LoggerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @group logger
     * @group logger-get-log
     */
    public function 現在のログを取得する ()
    {
        $log = Logger::getLog();
        $this->assertEquals('', $log);
    }


    /**
     * @test
     * @group logger
     * @group logger-add-log
     **/
    public function ログを追記する ()
    {
        Logger::addLog('test!');

        $log = Logger::getLog();
        $this->assertEquals('test!'.PHP_EOL, $log);
    }


    /**
     * @test
     * @group logger
     * @group logger-get-stacktrace
     **/
    public function エラー内容をトレースする ()
    {
        try {
            throw new \Exception('これはテストです');
        
        } catch (\Exception $e) {
            $log = Logger::getStackTrace($e);
            $this->assertTrue(is_string($log));
        }
    }
}
