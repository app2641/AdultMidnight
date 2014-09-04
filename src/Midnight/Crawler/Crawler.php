<?php


namespace Midnight\Crawler;

use Midnight\Crawler\Plugin\PluginInterface;
use Midnight\Utility\Logger;

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

            // RSSを取得
            $rss_dom = $this->plugin->fetchRss();

            // エントリ群を取得
            $entries = $this->plugin->getEntries($rss_dom);

            foreach ($entries as $entry) {
                // エントリを解析して必要データを取得する
                $this->crawl_data[] = $this->_parseEntry($entry);
            }
        
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


    /**
     * エントリを解析して必要データを配列で返す
     *
     * @param  DOMElement $entry
     * @return array
     **/
    private function _parseEntry ($entry)
    {
        $entry_date = $this->plugin->getEntryDate($entry);

        // 今日の登録されたエントリまたはDryRunでなければそのまま返す
        if (date('Y-m-d') != $entry_date &&
            $this->plugin->hasTestData() === false) return array();


        $url  = $this->plugin->getEntryUrl($entry);
        $html = $this->plugin->fetchHtml($url);

        $title    = $this->plugin->getEntryTitle($html);
        $eyecatch = $this->plugin->getEyeCatchUrl($html);
        $movies   = $this->plugin->getMoviesUrl($html);

        // ログの追記
        Logger::addLog($title);
        Logger::addLog($url);
        Logger::addLog('エントリ解析をしました'.PHP_EOL);

        return array(
            'title'     => $title,
            'url'       => $url,
            'eyecatch'  => $eyecatch,
            'image_src' => '',
            'movies'    => $movies
        );
    }
}
