<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;
use Midnight\Utility\CrawlerException;

class AdultAdult extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'あだるとあだると';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://tifer2.blog86.fc2.com/?xml';



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
        $query = 'div#main div#main_contents div.content h2.entry_header';
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
        $query = 'div#main div#main_contents div.content div.entry_body img';
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
        $query = 'div#main div#main_contents div.content div.entry_body div.more p a';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            $url = $manager->resolve($movies_el->plaintext);
            if ($url) {
                $movie_data[] = $url;
            }
        }

        return $movie_data;
    }
}

