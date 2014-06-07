<?php


namespace Midnight\Crawler;

use Midnight\Crawler\Plugin\PluginInterface;

class Crawler
{
    
    /**
     * @var Midnight\Crawler\Interface
     **/
    private $plugin;


    /**
     * クロールした情報を格納する配列
     *
     * @var array
     **/
    private $crawl_data = array();



    /**
     * @param  $plugin
     * @return void
     **/
    public function setPlugin (PluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }


    /**
     * クロール処理
     *
     * @return array
     **/
    public function crawl ()
    {
        try {
            $this->_validateParameters();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $this->crawl_data;
    }


    /**
     * パラメータのバリデート
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        if (is_null($this->plugin)) {
            throw new \Exception('プラグインが指定されていません');
        }
    }
}
