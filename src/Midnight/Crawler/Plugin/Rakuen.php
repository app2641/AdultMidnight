<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;

class Rakuen extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = '楽園エロ動画';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://rakuero-douga.com/feed';



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
        $query = 'div#container div#main div.post div#content div#postheading h1';
        $title_el = $html->find($query, 0);

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
        $query = 'div#container div#main div.post div#content div.kiji-l img';
        $img_el = $html->find($query, 0);

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
        $query = 'div#container div#main div.post div#content div.kiji-r iframe';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            $movie_data[] = $manager->resolve($movies_el->getAttribute('src'));
        }

        return $movie_data;
    }
}

