<?php


use Midnight\Crawler\Plugin\Doesu;
use Midnight\Crawler\Plugin\TestData\DoesuTestData;

class DoesuTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Doesu
     **/
    private $plugin;


    /**
     * @var DoesuTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new DoesuTestData();

        $this->plugin = new Doesu();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @group doesu
     * @group doesu-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group doesu
     * @group doesu-get-entries
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
     * @group doesu
     * @group doesu-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://xvideos-sm.com/archives/8992', $url);
    }


    /**
     * @test
     * @group doesu-get-entry-date
     * @group doesu
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-07-01', $date);
    }


    /**
     * @test
     * @medium
     * @group doesu-fetch-html
     * @group doesu
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group doesu-get-title
     * @group doesu
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals(
            '天海つばさ　可愛い巨乳のメイドちゃんが家の主人たちに毎晩肉奴隷として犯されマ●コが限界まで挿入され続けるｗｗｗｗ',
            $title
        );
    }


    /**
     * @test
     * @medium
     * @group doesu-get-eyecatch-url
     * @group doesu
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://xvideos-sm.com/thumb/dUL6wBBNShIlhj5idup8eDgp8BjgdAyV.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group doesu-get-movies-url
     * @group doesu
     */
    public function 動画へのリンクを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video7395344', $movies_url[0]);
        $this->assertEquals('http://jp.xvideos.com/video7395350', $movies_url[1]);
        $this->assertEquals('http://jp.xvideos.com/video7395352', $movies_url[2]);
        $this->assertEquals('http://jp.xvideos.com/video7395354', $movies_url[3]);
    }
}

