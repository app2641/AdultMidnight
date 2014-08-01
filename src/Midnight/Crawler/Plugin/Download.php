<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;

class Download extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = '無料エロ動画 xvideosダウンロード';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://xvideos-field.com/feed';



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
     * RSSが破損しているのか日付の値がおかしくなっているため独自整形をする
     *
     * 最後の0は不要
     * e.g. Thu, 03 Jul 2014 11:24:12 0
     *
     * @param $entry DOMElement
     * @return string
     */
    public function getEntryDate ($entry)
    {
        $date = $this->getNodeValueByTagName($entry, 'pubDate');
        $date = preg_replace('/\s0$/', '', $date);
        return date('Y-m-d', strtotime($date));
    }


    /**
     * エントリのタイトルを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEntryTitle ($html)
    {
        $query = 'article.content header.content__header h1.content__h1';
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
        $query = 'div.content__body ul li.kiji img';
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
        $query = 'div.content__body ul li.kiji iframe';
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

