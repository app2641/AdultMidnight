<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;
use Midnight\Utility\CrawlerException;

class HentaiAnime extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'HENTAIアニメちゃんねる';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://hentaianimechannel.blog.fc2.com/?xml';



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
        $query = 'div.entry h2';
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
        $query = 'div.entry div.textBody div.centeringtext p.image img';
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
        $query = 'div.entry div.textBody div.centeringtext p a button.button-default';
        $movies_els = $html->find($query);
        $movie_data = array();
        $manager    = new UriManager();

        // 動画はこちらテキストのリンクを取得する
        foreach ($movies_els as $movies_el) {
            // 親要素のaタグを取得する
            $parent = $movies_el->parentNode();
            if ($parent->nodeName() === 'a') {
                $url = $manager->resolve($parent->getAttribute('href'));
                if ($url) {
                    $movie_data[] = $url;
                }
            }
        }

        return $movie_data;
    }
}

