<?php

namespace Midnight\Crawler\Plugin\TestData;

abstract class AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name;

    /**
     * @var array
     **/
    protected $html_paths;

    /**
     * テスト用RSSデータへのパスを返す
     *
     * @return string
     **/
    public function getRssPath ()
    {
        $rss_path = ROOT.'/data/fixtures/rss/'.$this->rss_name;
        return $rss_path;
    }

    /**
     * テスト用Htmlデータのパス群を返す
     *
     * @return array
     **/
    public function getHtmlPaths ()
    {
        return $this->html_paths;
    }
}
