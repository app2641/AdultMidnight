<?php


namespace Midnight\Crawler\Plugin;

interface PluginInterface
{

    /**
     * DOMDocumentからエントリのURLを取得する
     *
     * @param  DOMDocument $entry
     * @return string
     **/
    public function getEntryUrl ($entry);


    /**
     * DOMDocumentからエントリの登録日時を取得する
     *
     * @param  DOMDocument $entry
     * @return string
     **/
    public function getEntryDate ($entry);


    /**
     * HTMLからエントリのタイトルを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEntryTitle ($html);


    /**
     * HTMLからアイキャッチ画像のURLを取得する
     *
     * @param  simple_html_dom $html
     * @return string
     **/
    public function getEyeCatchUrl ($html);


    /**
     * HTMLから動画のURLを取得する
     *
     * @param  simple_html_dom $html
     * @return array
     **/
    public function getMoviesUrl ($html);
}
