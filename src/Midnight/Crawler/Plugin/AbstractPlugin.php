<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\Plugin\TestData\AbstractTestData;
use Midnight\Utility\CrawlerException;

require_once LIB.'/Library/SimpleHtmlDomParser/simple_html_dom.php';

abstract class AbstractPlugin
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name;


    /**
     * DryRun設定
     *
     * @var AbstractTestData
     **/
    protected $test_data;


    /**
     * @return string
     **/
    public function getSiteName ()
    {
        return $this->site_name;
    }


    /**
     * DryRun設定を切り替える
     *
     * @param  AbstractTestData $test_data
     * @return void
     **/
    public function setTestData (AbstractTestData $test_data)
    {
        $this->test_data = $test_data;
    }


    /**
     * テストデータを所持しているかを判別する
     *
     * @return boolean
     **/
    public function hasTestData ()
    {
        return (is_null($this->test_data)) ? false: true;
    }


    /**
     * RSSを取得する
     *
     * @return DOMDocument
     **/
    public function fetchRss ()
    {
        try {
            if (! is_null($this->test_data)) {
                $rss_xml = file_get_contents($this->test_data->getRssPath());
            } else {
                $rss_xml = file_get_contents($this->rss_url);
            }

            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->loadXML($rss_xml);

        } catch (\Exception $e) {
            throw new CrawlerException('RSSを取得出来ませんでした');
        }

        return $dom;
    }


    /**
     * コンテンツ要素を取得する
     *
     * @param  DOMDocument $dom
     * @return DOMNodeList
     **/
    public function getEntries ($dom)
    {
        return $dom->getElementsByTagName('item');
    }


    /**
     * 指定要素の値を取得する
     *
     * @param  DOMElement $element
     * @param  string $tag_name
     * @return string
     **/
    public function getNodeValueByTagName ($element, $tag_name)
    {
        $elements = $element->getElementsByTagName($tag_name);
        if ($elements->length === 0) throw new CrawlerException($tag_name.'要素は見つかりませんでした');

        return $elements->item(0)->nodeValue;
    }


    /**
     * Pubdate要素からエントリの登録日を取得する
     *
     * @param  DOMElement $element
     * @return string
     **/
    public function getDateByPubDate ($element)
    {
        $date = $this->getNodeValueByTagName($element, 'pubDate');
        return date('Y-m-d', strtotime($date));
    }


    /**
     * published要素からエントリの登録日を取得する
     *
     * @param  DOMElement $element
     * @return string
     **/
    public function getDateByPublished ($element)
    {
        $date = $this->getNodeValueByTagName($element, 'published');
        return date('Y-m-d', strtotime($date));
    }


    /**
     * dc:date要素からエントリの登録日を取得する
     *
     * @param  DOMElement $element
     * @return string
     **/
    public function getDateByDcDate ($element)
    {
        $date = $this->getNodeValueByTagName($element, 'date');
        return date('Y-m-d', strtotime($date));
    }


    /**
     * HTMLを取得する
     *
     * @param  string $url
     * @return
     **/
    public function fetchHtml ($url = false)
    {
        if (! is_null($this->test_data)) {
            $html_paths = $this->test_data->getHtmlPaths();
            $html_path  = ROOT.'/data/fixtures/html/';

            // テストデータを示したurlかどうか
            if (in_array($url, $html_paths)) {
                $html_path .= $url;
            } else {
                $html_path .= $html_paths[0];
            }
            $html = str_get_html(file_get_contents($html_path));

        } else {
            $html = file_get_html($url);
        }

        if ($html === false) throw new CrawlerException('htmlが取得出来ませんでした');

        return $html;
    }
}

