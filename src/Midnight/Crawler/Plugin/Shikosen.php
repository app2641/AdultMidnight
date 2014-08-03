<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;

class Shikosen extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'シコセン';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://hikaritube.com/atom.rdf';


    /**
     * エントリのURL
     *
     * @var string
     **/
    public $entry_url;


    /**
     * DOMElementからエントリのURLを返す
     *
     * @param  $entry DOMElement
     * @return string
     **/
    public function getEntryUrl ($entry)
    {
        $this->entry_url = $this->getNodeValueByTagName($entry, 'link');
        return $this->entry_url;
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
        $query = 'div#wrapper div#main h1';
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
        // エントリページにアイキャッチ画像がないため
        // トップページから画像のurlを取得する
        $html    = $this->fetchHtml('http://hikaritube.com');
        $img_url = $this->getEyeCatchUrlFromTopPage($html);
        $html->clear();

        return $img_url;
    }


    /**
     * トップページを解析してアイキャッチを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEyeCatchUrlFromTopPage ($html)
    {
        $link   = str_replace('http://hikaritube.com/', '', $this->entry_url);
        $query  = sprintf('div#main div#cntArea div.cnt p.thumb a[href="%s"] img', $link);
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
        $query = 'div#player div.section object embed[src="http://static.xvideos.com/swf/flv_player_site_v4.swf"]';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            if (! $movies_el->hasAttribute('flashvars')) continue;

            $video_id = $movies_el->getAttribute('flashvars');
            $video_id = str_replace('id_video=', '', $video_id);

            $url = sprintf('http://jp.xvideos.com/video%s', $video_id);
            $movie_data[] = $url;
        }

        return $movie_data;
    }
}

