<?php


use Midnight\Crawler\Plugin\Youskbe;
use Midnight\Crawler\Plugin\TestData\YouskbeTestData;

class YouskbeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Youskbe
     **/
    private $plugin;


    /**
     * @var YouskbeTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new YouskbeTestData();

        $this->plugin = new Youskbe;
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @group youskbe
     * @group youskbe-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group youskbe
     * @group youskbe-get-entries
     */
    public function コンテンツ要素を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);
        $this->assertInstanceOf('DOMNodeList', $entries);
        $this->assertFalse(is_null($entries->item(0)));
    }


    /**
     * @test
     * @group youskbe
     * @group youskbe-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://www.youskbe.com/blog/archives/2014/06/11_161548.php', $url);
    }


    /**
     * @test
     * @group youskbe-get-entry-date
     * @group youskbe
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-06-11', $date);
    }


    /**
     * @test
     * @medium
     * @group youskbe-fetch-html
     * @group youskbe
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group youskbe-get-title
     * @group youskbe
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【愛内希】萌えるコスプレ作品！【XVideos】', $title);
    }


    /**
     * @test
     * @medium
     * @group youskbe-get-eyecatch-url
     * @group youskbe
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://www.youskbe.com/img2/12/07/281121.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group youskbe-get-movies-url
     * @group youskbe
     */
    public function 動画へのリンクを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video7953646/', $movies_url[0]);
        $this->assertEquals('http://jp.xvideos.com/video7953677/', $movies_url[1]);

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://videomega.tv/?ref=EffeTZEYAQ', $movies_url[0]);

    }
}

