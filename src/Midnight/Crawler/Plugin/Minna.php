<?php


namespace Midnight\Crawler\Plugin;

use Midnight\Crawler\UriManager;

class Minna extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'みんなが抜いたエロ動画';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://eropeg.net/index.rdf';



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
        $query = 'div.article-date h2.article-title a';
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
        $query = 'div.articlebody a img.pict';
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
        $query = 'div.article-body-inner div.article-body-more';
        $more_el = $html->find($query, 0);
        $movie_data = array();
        $manager    = new UriManager();

        // 子供要素を持っていなければならない
        if (! $more_el->hasChildNodes()) return $movie_data;
        $child_els = $more_el->childNodes();


        foreach ($child_els as $child_el) {

            // scriptタグであった場合
            if ($child_el->tag == 'script') {
                // 主にFC2動画をembedしている場合
                if ($child_el->hasAttribute('url')) {
                    $movie_url = $child_el->getAttribute('url');
                    $movie_data[] = $manager->resolve($movie_url);
                    continue;
                }

                // 主にasg.toの動画をembedしている場合
                if ($child_el->hasAttribute('src') &&
                    $child_el->getAttribute('src') == 'http://asg.to/js/past_uraui.js') {
                    // 次のスクリプト要素内にある関数に与えているmcd値を取得する
                    // e.g. Purauifla("mcd=lNl25A52tkqoweP2", 450, 372); --> lNl25A52tkqoweP2
                    $next_el = $child_el->nextSibling();

                    $pattern = '/Purauifla\("mcd=([0-9a-zA-Z]+)", [0-9]+, [0-9]+\);/';
                    preg_match($pattern, $next_el->innertext, $matches);
                    if (! isset($matches[1])) continue;

                    $movie_url = 'http://asg.to?mcd='.$matches[1];
                    $movie_data[] = $manager->resolve($movie_url);
                }
            }


            // iframeタグであった場合
            if ($child_el->tag == 'a') {
                $url = $child_el->getAttribute('href');
                if (! $manager->isXvideosUrl($url)) {
                    continue;
                }

                $movie_data[] = $manager->resolve($url);
            }
        }

        return $movie_data;
    }
}

