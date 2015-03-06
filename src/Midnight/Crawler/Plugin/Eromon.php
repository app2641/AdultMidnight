<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;
use Midnight\Utility\CrawlerException;

class Eromon extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'えろもん';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://erovi0.blog.fc2.com/?xml';



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
        $query = 'div#container div#content div.entry_body div.entry_middle h2 a';
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
        $query = 'div#container div#content div.entry_body div.entry_middle div.entry_text div img';
        $img_el = $html->find($query, 0);

        // img要素の親にdivを挟んでいるパターンのページも存在するため
        // 二重でクエリを発行して確認している
        if (is_null($img_el)) {
            $query = 'div#container div#content div.entry_body div.entry_middle div.entry_text img';
            $img_el = $html->find($query, 0);
        }
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
        $query = 'div#container div#content div.entry_middle div.entry_text iframe';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            // iframeの次の次の要素であるa要素を取得する
            $link_el = $this->_fetchMovieLinkElement($movies_el);
            if (is_null($link_el)) continue;

            if ($link_el->nodeName() === 'a') {
                $movie_data[] = $manager->resolve($link_el->getAttribute('href'));
            }
        }

        if (count($movie_data) > 0) return $movie_data;


        // div.entry_more_text以下にiframeがある場合もある
        $query = 'div#container div#content div.entry_middle div.entry_more_text iframe';
        $movies_els = $html->find($query);

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            // iframeの次の次の要素であるa要素を取得する
            $link_el = $this->_fetchMovieLinkElement($movies_el);
            if (is_null($link_el)) continue;

            if ($link_el->nodeName() === 'a') {
                $movie_data[] = $manager->resolve($link_el->getAttribute('href'));
            }
        }

        return $movie_data;
    }


    /**
     * 動画urlの指定されたa要素を取得する
     *
     * @param  simple_html_dom_node $el
     * @return simple_html_dom_node
     **/
    private function _fetchMovieLinkElement ($el)
    {
        $next_el = $el->nextSibling();
        if (is_null($next_el)) return $next_el;

        return $next_el->nextSibling();
    }
}

