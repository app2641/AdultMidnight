<?php

namespace Midnight\Crawler;

use Midnight\Crawler\Plugin\PluginInterface;

use Midnight\Utility\CrawlerException;
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
        try {
            $entry_date = $this->plugin->getEntryDate($entry);

            // 今日の登録されたエントリではない、またはTestData使用でなければそのまま返す
            if (date('Y-m-d') != $entry_date &&
                $this->plugin->hasTestData() === false) return array();

            $url = false;
            $url = $this->plugin->getEntryUrl($entry);

            $html     = $this->plugin->fetchHtml($url);
            $title    = $this->plugin->getEntryTitle($html);
            $eyecatch = $this->plugin->getEyeCatchUrl($html);
            $movies   = $this->plugin->getMoviesUrl($html);

            $result = array(
                'title'     => $title,
                'url'       => $url,
                'eyecatch'  => $eyecatch,
                'image_src' => '',
                'movies'    => $movies
            );

        } catch (CrawlerException $e) {
            if ($url !== false) Logger::addLog($url);
            Logger::addLog($e->getMessage());
            Logger::addLog($this->plugin->getSiteName().PHP_EOL);
            $result = array();

        } catch (\Exception $e) {
            if ($url !== false) Logger::addLog($url);
            Logger::addLog($e->getMessage());
            Logger::addLog(Logger::getStackTrace($e).PHP_EOL);
            $result = array();
        }
        return $result;
    }
}
