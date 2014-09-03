<?php


namespace Midnight\Utility;

class Tracer
{

    /**
     * エラーの詳細をトレースしてログを返す
     *
     * @param  Exception $e
     * @return string
     **/
    public static function getLog (\Exception $e)
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

