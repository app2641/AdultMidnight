<?php


namespace Midnight\Crawler\Plugin;

require_once LIB.'/Library/SimpleHtmlDomParser/simple_html_dom.php';

abstract class AbstractPlugin
{


    /**
     * RSSを取得する
     *
     * @param  string $rss_xml  rssテキストの場合はこれを解析する
     * @return DOMDocument
     **/
    public function fetchRss ($rss_xml = null)
    {
        try {
            if (is_null($rss_xml)) {
                $rss_xml = file_get_contents($this->rss_url);
            }

            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->loadXML($rss_xml);
        
        } catch (\Exception $e) {
            throw $e;
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
     * HTMLを取得する
     *
     * @param  string $url
     * @param  boolean $dry_run
     * @return 
     **/
    public function fetchHtml ($url, $dry_run = false)
    {
        if ($dry_run) {
            $html_path = ROOT.'/data/fixtures/html/'.$url;
            $html = str_get_html(file_get_contents($html_path));
        } else {
            $html = file_get_html($url);
        }

        return $html;
    }
}

