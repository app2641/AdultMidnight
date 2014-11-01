<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;

class EroEro extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'エロエロ速報';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://ero2sokuhou.jp/?xml';



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
        $query = 'div#center-left div#center div.ently_outline h2.ently_title a';
        $title_el = $html->find($query, 0);
        if (is_null($title_el)) throw new \Exception('タイトルを取得できませんでした');

        return trim($title_el->plaintext);
    }


    /**
     * アイキャッチ画像のURLを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEyeCatchUrl ($html)
    {
        $query = 'div.ently_outline div.ently_body div.ently_text img';
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
        $query = 'div.ently_body div.ently_text div.video-container iframe';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            if ($movies_el->hasAttribute('src')) {
                $url = $manager->resolve($movies_el->getAttribute('src'));
                if ($url !== false) $movie_data[] = $url;
            }
        }

        $query = 'div.ently_outline div.ently_body a';
        $movies_els = $html->find($query);
        foreach ($movies_els as $movies_el) {
            $text = $movies_el->plaintext;
            if (preg_match('/リンク（/', $text) && $movies_el->hasAttribute('href')) {
                $resolve_url = $manager->resolve($movies_el->getAttribute('href'));
                if ($resolve_url) $movie_data[] = $resolve_url;
            }
        }

        return $movie_data;
    }
}

