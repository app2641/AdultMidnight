<?php


namespace Midnight\Exception;

class MidnightException extends \Exception
{

    /**
     * エラーの詳細をトレースして返す
     *
     * @return string
     **/
    public function getErrorTrace ()
    {
        $text = $this->getMessage().PHP_EOL.
            $this->getFile().' on line '.$this->getLine().PHP_EOL.PHP_EOL;

        foreach ($this->getTrace() as $error) {

            if (isset($error['class'])) {
                $text .= 'Class: '.$error['class'].PHP_EOL;
            }
            $text .= 'Function: '.$error['function'].PHP_EOL.PHP_EOL;
        }

        return $text;
    }
}

