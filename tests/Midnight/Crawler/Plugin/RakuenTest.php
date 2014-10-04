<?php


use Midnight\Crawler\Plugin\Rakuen;
use Midnight\Crawler\Plugin\TestData\RakuenTestData;

class RakuenTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Rakuen
     **/
    private $plugin;


    /**
     * @var RakuenTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new RakuenTestData();

        $this->plugin = new Rakuen();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @group rakuen
     * @group rakuen-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group rakuen
     * @group rakuen-get-entries
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
     * @group rakuen
     * @group rakuen-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://rakuero-douga.com/archives/6640', $url);
    }


    /**
     * @test
     * @group rakuen-get-entry-date
     * @group rakuen
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-07-17', $date);
    }


    /**
     * @test
     * @large
     * @group rakuen-fetch-html
     * @group rakuen
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @large
     * @group rakuen-get-title
     * @group rakuen
     */
    public function エントリのタイトルを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('おさげ頭の黒髪少女と父が繰り広げる変態相姦調教セクロスがエロすぎｗｗｗ', $title);
    }


    /**
     * @test
     * @large
     * @group rakuen-get-eyecatch-url
     * @group rakuen
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://rakuero-douga.com/wp-content/uploads/2014/07/20ab46020e29eac11be2080c373bb780.jpg', $img_url);
    }


    /**
     * @test
     * @large
     * @group rakuen-get-movies-url
     * @group rakuen
     */
    public function 動画へのリンクを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video5895407', $movies_url[0]);
    }
}