<?php

namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;
use Midnight\Utility\CrawlerException;

class EroAnime extends AbstractPlugin implements PluginInterface
{
    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = '無料エロアニメ動画';

    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://anime-erodouga.com/index.xml';

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
        $query = 'div#alpha div#alpha-inner div.entry-asset div.page-title h1.entry-title';
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
        $query = 'div#alpha-inner div.entry-asset div.entry-content div.asset-body a img';
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
        $query = 'div#more a';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $el) {
            if (! $el->hasAttribute('href')) continue;
            $url  = $el->getAttribute('href');
            $info = parse_url($url);

            // 関係ないurlが含まれている場合があるためチェックする
            if ($info['host'] == 'ero-erodouga.com') continue;
            $movie_data[] = $manager->resolve($url);
        }

        return $movie_data;
    }
}
