<?php

namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;
use Midnight\Utility\CrawlerException;

class Epusta extends AbstractPlugin implements PluginInterface
{
    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'えぷすた';

    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://av0yourfilehost.blog35.fc2.com/?xml';

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
        $query = 'div#center div.ently_outline h2.ently_title a';
        $title_el = $html->find($query, 0);
        if (is_null($title_el)) throw new CrawlerException('タイトルを取得出来ませんでした');

        $title = trim(str_replace('&nbsp;', '', $title_el->plaintext));
        return $title;
    }

    /**
     * アイキャッチ画像のURLを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEyeCatchUrl ($html)
    {
        $query = 'div.ently_body div.ently_text table tbody tr td table tbody tr td a img';
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
        $query = 'div.ently_body div.ently_text a';
        $movies_els = $html->find($query);
        $movie_data = array();
        $link_stack = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            // href属性とplaintextが合致するもの、かつ重複していないリンクを格納する
            $link = $movies_el->getAttribute('href');
            $text = $movies_el->plaintext;
            if ($link == $text && !in_array($link, $link_stack)) {
                $movie_data[] = $manager->resolve($movies_el->getAttribute('href'));
                $link_stack[] = $link;
            }

        }

        return $movie_data;
    }
}
