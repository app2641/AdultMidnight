<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;

class AdultGeek extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'アダルトギーク';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://www.adultgeek.net/index.xml';



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
        return $this->getDateByPubDate($entry);
    }


    /**
     * エントリのタイトルを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEntryTitle ($html)
    {
        $query = 'h2[id="archive-title]';
        $title_el = $html->find($query, 0);
        if (is_null($title_el)) throw new \Exception('タイトルを取得できませんでした');

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
        $query = 'div.entry-content div.entry-body p.contents-img a img';
        $img_el = $html->find($query, 0);

        if (is_null($img_el)) throw new \Exception('アイキャッチを取得できませんでした');
        if (!$img_el->hasAttribute('src')) throw new \Exception('src属性が見つかりませんでした');

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
        $query = 'div.entry div.entry-content div#more p a';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            if (preg_match('/動画[0-9０-９]*はこちら/', $movies_el->plaintext)) {
                $movie_data[] = $manager->resolve($movies_el->getAttribute('href'));
            }
        }

        return $movie_data;
    }
}
