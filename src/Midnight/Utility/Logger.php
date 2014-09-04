<?php


namespace Midnight\Utility;

use Sapphire\Utility\Registry;

class Logger
{

    /**
     * 現在のログを取得する
     *
     * @return string
     **/
    public static function getLog ()
    {
        $registry = Registry::getInstance();

        if (! $registry->ifKeyExists('log')) {
            $log = '';
            Registry::set('log', $log);

        } else {
            $log = Registry::get('log');
        }

        return $log;
    }


    /**
     * ログを追記する
     *
     * @param  string $text
     * @return void
     **/
    public static function addLog ($text)
    {
        $log = Logger::getLog();
        $log .= $text.PHP_EOL;

        Registry::set('log', $log);
    }


    /**
     * エラーの詳細をトレースしてログを返す
     *
     * @param  Exception $e
     * @return string
     **/
    public static function getStackTrace (\Exception $e)
    {
        $text = $e->getMessage().PHP_EOL.
            $e->getFile().' on line '.$e->getLine().PHP_EOL.PHP_EOL;

        foreach ($e->getTrace() as $error) {

            if (isset($error['class'])) {
                $text .= 'Class: '.$error['class'].PHP_EOL;
            }
            $text .= 'Function: '.$error['function'].PHP_EOL.PHP_EOL;
        }

        return $text;
    }
}

