<?php


use Midnight\Crawler\Plugin\EroEro,
    Midnight\Crawler\Plugin\TestData\EroEroTestData;

class EroEroTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EroEro
     **/
    private $plugin;


    /**
     * @var EroEroTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new EroEroTestData();

        $this->plugin = new EroEro();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @group eroero
     * @group eroero-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group eroero
     * @group eroero-get-entries
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
     * @group eroero
     * @group eroero-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://ero2sokuhou.jp/blog-entry-1873.html', $url);
    }


    /**
     * @test
     * @group eroero-get-entry-date
     * @group eroero
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-10-13', $date);
    }


    /**
     * @test
     * @medium
     * @group eroero-fetch-html
     * @group eroero
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group eroero-get-title
     * @group eroero
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals(
            '【成瀬心美】 JKチアコス娘とSEXしたったｗｗｗｗｗｗｗｗｗｗｗ 【xhamster】【pornhub】',
            $title
        );
    }


    /**
     * @test
     * @medium
     * @group eroero-get-eyecatch-url
     * @group eroero
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-70.fc2.com/e/r/o/ero2sokuhou/49ekdv261pl.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group eroero-get-movies-url
     * @group eroero
     */
    public function 動画へのリンクを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://xhamster.com/xembed.php?video=2832908', $movies_url[0]);
        $this->assertEquals('http://www.pornhub.com/embed/1016065864', $movies_url[1]);
    }


    /**
     * @test
     * @medium
     * @group eroero-get-movies-url2
     * @group eroero
     */
    public function 動画へのリンクを取得する2 ()
    {
        // 広告ページ
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));
    }


    /**
     * @test
     * @medium
     * @group eroero-get-movies-url3
     * @group eroero
     */
    public function 動画へのリンクを取得する3 ()
    {
        // 広告ページ
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://www.pornhub.com/view_video.php?viewkey=1879097375', $movies_url[0]);
    }


    /**
     * @test
     * @medium
     * @group eroero-get-movies-url4
     * @group eroero
     */
    public function 動画へのリンクを取得する4 ()
    {
        // 広告ページ
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[3]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://www.pornhub.com/view_video.php?viewkey=1947857414', $movies_url[0]);
        $this->assertEquals(
            'http://www.thisav.com/video/81445/%84%26frac14%3B%81%87soe-402-3d%26frac34%3B%8E%82%86%81%26frac34%3B-%8Bae%26frac12%3B%93%98%83%8F%81%85%81%9B%82%8Bae%8Abody%82%83%83%82%82%26sup1%3B-%26frac34%3B%8E%82%86%81%26frac34%3B.html',
            $movies_url[1]
        );
    }


    /**
     * @test
     * @medium
     * @group eroero-get-movies-url5
     * @group eroero
     */
    public function 動画へのリンクを取得する5 ()
    {
        // 広告ページ
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[4]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));
    }


    /**
     * @test
     * @medium
     * @group eroero-get-movies-url6
     * @group eroero
     */
    public function 動画へのリンクを取得する6 ()
    {
        // 広告ページ
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[5]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://xhamster.com/xembed.php?video=3236805', $movies_url[0]);
    }


    /**
     * @test
     * @medium
     * @group eroero-get-movies-url7
     * @group eroero
     */
    public function 動画へのリンクを取得する7 ()
    {
        // 広告ページ
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[6]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));
    }
}

