<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;

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
        $query = 'div.container article.entry h1.entry_title';
        $el = $html->find($query, 0);
        if (is_null($el)) throw new \Exception('アイキャッチを取得できませんでした');

        $img_el = $el->nextSibling()->firstChild()->firstChild();
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
        $query = 'article.entry div#more a';
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
