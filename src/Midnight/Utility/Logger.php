<?php

namespace Midnight\Utility;

use Sapphire\Utility\Registry;

class Logger
{
    /**
     * ログを初期化する
     *
     * @return void
     */
    public static function init ()
    {
        Registry::set('log', '');
    }

    /**
     * 現在のログを取得する
     *
     * @return string
     **/
    public static function getLog ()
    {
        $registry = Registry::getInstance();

        if (! $registry->ifKeyExists('log')) {
            Logger::init();
        }

        return Registry::get('log');
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
            $text .= 'Function: '.$error['function'];
            
            if (isset($error['line'])) {
                $text .= ' on '.$error['line'];
            }
            $text .= PHP_EOL.PHP_EOL;
        }

        return $text;
    }
}
