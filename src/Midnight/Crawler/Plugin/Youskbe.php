<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;
use Midnight\Utility\CrawlerException;

class Youskbe extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'ゆうすけべぶろぐ';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://www.youskbe.com/blog/atom.xml';



    /**
     * コンテンツの要素を取得する
     *
     * @param  DOMDocument $dom
     * @return DOMNodeList
     **/
    public function getEntries ($dom)
    {
        return $dom->getElementsByTagName('entry');
    }


    /**
     * DOMElementからエントリのURLを返す
     *
     * @param  $entry DOMElement
     * @return string
     **/
    public function getEntryUrl ($entry)
    {
        $link_el = $entry->getElementsByTagName('link')->item(0);
        return $link_el->getAttribute('href');
    }


    /**
     * エントリの登録された日付を取得する
     *
     * @param $entry DOMElement
     * @return string
     */
    public function getEntryDate ($entry)
    {
        return $this->getDateByPublished($entry);
    }


    /**
     * エントリのタイトルを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEntryTitle ($html)
    {
        $query = 'h1.entry_title';
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
        $query = 'div.entry article h1.entry_title';
        $el = $html->find($query, 0);
        if (is_null($el)) throw new CrawlerException('アイキャッチを取得出来ませんでした');

        $img_el = $el->nextSibling()->firstChild()->firstChild();
        if (!$img_el) throw new CrawlerException('img要素が見つかりませんでした');
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
        $query = 'div.entry article div#more a';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            $url  = $movies_el->getAttribute('href');
            $text = $movies_el->innertext;

            if ($url === $text) {
                $movie_data[] = $manager->resolve($url);
            }
        }

        return $movie_data;
    }
}

