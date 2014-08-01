<?php


use Midnight\Crawler\Plugin\AdultGeek;
use Midnight\Crawler\Plugin\TestData\AdultGeekTestData;

class AdultGeekTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AdultGeek
     **/
    private $plugin;


    /**
     * @var AdultGeekTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new AdultGeekTestData();

        $this->plugin = new AdultGeek;
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @group geek
     * @group geek-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group geek
     * @group geek-get-entries
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
     * @group geek
     * @group geek-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://www.adultgeek.net/2014/06/post_5459.html', $url);
    }


    /**
     * @test
     * @group geek-get-entry-date
     * @group geek
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-06-07', $date);
    }


    /**
     * @test
     * @medium
     * @group geek-fetch-html
     * @group geek
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group geek-get-title
     * @group geek
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【エロ動画】 真夏の海！水着ギャルをミラー号に乗せて猥褻本番マッサージ企画！', $title);
    }


    /**
     * @test
     * @medium
     * @group geek-get-eyecatch-url
     * @group geek
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://www.adultgeek.net/upimg/1406/mmgouhiyake.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group geek-get-movies-url
     * @group geek
     */
    public function 動画へのリンクを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://vid.bz/watch/yW8Xp5', $movies_url[0]);
    }
}

