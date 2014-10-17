<?php


use Midnight\Crawler\Plugin\AdultVideo,
    Midnight\Crawler\Plugin\TestData\AdultVideoTestData;

class AdultVideoTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AdultVideo
     **/
    private $plugin;


    /**
     * @var AdultVideoTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new AdultVideoTestData();

        $this->plugin = new AdultVideo();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @group adultvideo
     * @group adultvideo-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group adultvideo
     * @group adultvideo-get-entries
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
     * @group adultvideo
     * @group adultvideo-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://av1gou.blog123.fc2.com/blog-entry-13160.html', $url);
    }


    /**
     * @test
     * @group adultvideo-get-entry-date
     * @group adultvideo
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-10-14', $date);
    }


    /**
     * @test
     * @medium
     * @group adultvideo-fetch-html
     * @group adultvideo
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group adultvideo-get-title
     * @group adultvideo
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('◆尻軽人妻◆ナンパにあっさりとついてきた人妻を電マ責めしてヨガらせまくる！', $title);
    }


    /**
     * @test
     * @medium
     * @group adultvideo-get-eyecatch-url
     * @group adultvideo
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-67.fc2.com/a/v/1/av1gou/aaa141014s.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group adultvideo-get-movies-url
     * @group adultvideo
     */
    public function 動画へのリンクを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video2960239', $movies_url[0]);
    }


    /**
     * @test
     * @medium
     * @group adultvideo-get-movies-url2
     * @group adultvideo
     */
    public function 動画へのリンクを取得する2 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://xhamster.com/xembed.php?video=3590551', $movies_url[0]);
    }
}

