<?php


namespace Midnight\Crawler\Plugin;

class AdultGeek extends AbstractPlugin implements PluginInterface
{

    /**
     * サイト名
     *
     * @var string
     **/
    protected $site_name = 'アダルトギーク';


    /**
     * RSSフィードのURL
     *
     * @var string
     **/
    protected $rss_url = 'http://www.adultgeek.net/index.xml';



    /**
     * DOMElementからコンテンツのURLを返す
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
        $query = 'h2[id="archive-title]';
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
        $query = 'div.entry-content div.entry-body p.contents-img a img';
        $img_el = $html->find($query, 0);

        return $img_el->getAttribute('src');
    }
}

