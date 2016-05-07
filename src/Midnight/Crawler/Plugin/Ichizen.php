<?php

namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;
use Midnight\Utility\CrawlerException;

class Ichizen extends AbstractPlugin implements PluginInterface
{
    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = '一日一善';

    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://eroichizen.com/?xml';

    /**
     * DOMElementからエントリのURLを返す
     *
     * @param  $entry DOMElement
     * @return string
     **/
    public function getEntryUrl ($entry)
    {
        return $this->getNodeValueByTagName($entry, 'link');
    }

    /**
     * エントリの登録された日付を取得する
     *
     * @param $entry DOMElement
     * @return string
     */
    public function getEntryDate ($entry)
    {
        return $this->getDateByDcDate($entry);
    }

    /**
     * エントリのタイトルを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEntryTitle ($html)
    {
        $query = 'div.entry_d div.entry h2.entryTitle a';
        $title_el = $html->find($query, 0);
        if (is_null($title_el)) throw new CrawlerException('タイトルを取得出来ませんでした');

        return $title_el->plaintext;
    }

    /**
     * アイキャッチ画像のURLを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEyeCatchUrl ($html)
    {
        $query = 'div.entry div.entryBody center img';
        $img_el = $html->find($query, 0);

        if (is_null($img_el)) throw new CrawlerException('アイキャッチを取得出来ませんでした');
        if (!$img_el->hasAttribute('src')) throw new CrawlerException('src属性が見つかりませんでした');

        return $img_el->getAttribute('src');
    }

    /**
     * 動画のURLを取得する
     *
     * @param  simple_html_dom $html
     * @return array
     **/
    public function getMoviesUrl ($html)
    {
        $query = 'div.entryBody div.topmore a img';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            if (! preg_match('/^動画.+/', $movies_el->getAttribute('alt'))) continue;

            // 親のaタグからリンクを取得する
            $parent_el = $next_el = $movies_el->parentNode();
            $i = 0;
            while ($i < 3) {
                $next_el = $next_el->nextSibling();
                if (is_null($next_el)) break;
                $i++;
            }
            if ($next_el->nodeName() == 'span') {
                $movie_data = [];
                break;
            }

            if ($parent_el->nodeName() == 'a') {
                $movie_data[] = $manager->resolve($parent_el->getAttribute('href'));
            }
        }

        return $movie_data;
    }
}
