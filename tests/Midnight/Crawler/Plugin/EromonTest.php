<?php


use Midnight\Crawler\Plugin\Eromon,
    Midnight\Crawler\Plugin\TestData\EromonTestData;

class EromonTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Eromon
     **/
    private $plugin;


    /**
     * @var EromonTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new EromonTestData();

        $this->plugin = new Eromon();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    RSSを取得出来ませんでした
     * @group eromon
     * @group eromon-valid-rss
     */
    public function RSSが取得出来なかった場合 ()
    {
        $test_data = $this->getMock(
            'Midnight\Crawler\Plugin\TestData\EromonTestData',
            array('getRssPath')
        );
        $test_data->expects($this->any())
            ->method('getRssPath')->will($this->returnValue('valid path'));

        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $this->plugin->setTestData($test_data);
        $this->plugin->fetchRss();
    }


    /**
     * @test
     * @group eromon
     * @group eromon-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group eromon
     * @group eromon-get-entries
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
     * @group eromon
     * @group eromon-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertEquals('http://erovi0.blog.fc2.com/blog-entry-4370.html', $url);
    }


    /**
     * @test
     * @group eromon-get-entry-date
     * @group eromon
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertEquals('2015-03-03', $date);
    }


    /**
     * @test
     * @medium
     * @group eromon-fetch-html
     * @group eromon
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group eromon-get-title
     * @group eromon
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('メルシーボークー DV 16 放課後Hなアルバイト : 尾上若葉', $title);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    タイトルを取得出来ませんでした
     * @group eromon-not-get-title
     * @group eromon
     */
    public function エントリのタイトルを取得出来なかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[4]);
        $this->plugin->getEntryTitle($html);
    }


    /**
     * @test
     * @medium
     * @group eromon-get-eyecatch-url
     * @group eromon
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);
        $this->assertEquals('http://blog-imgs-75-origin.fc2.com/e/r/o/erovi0/MCDV-16.jpg', $img_url);


        // img要素の親にdivを挟んでいるパターンのページ
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $img_url = $this->plugin->getEyeCatchUrl($html);
        $this->assertEquals('http://blog-imgs-75-origin.fc2.com/e/r/o/erovi0/apak088sops.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    アイキャッチを取得出来ませんでした
     * @group eromon-not-get-eyecatch-img-el
     * @group eromon
     */
    public function アイキャッチの画像要素が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[4]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    src属性が見つかりませんでした
     * @group eromon-not-get-img-src
     * @group eromon
     */
    public function src属性が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[5]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @medium
     * @group eromon-get-movies-url1
     * @group eromon
     */
    public function 動画へのリンクを取得する1 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(
            'http://jp.xhamster.com/movies/3958080/wakaba_onoue_enjoys_2_cocks_by_packmans.html',
            $movies_url[0]
        );
    }


    /**
     * @test
     * @medium
     * @group eromon-get-movies-url2
     * @group eromon
     */
    public function 動画へのリンクを取得する2 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(
            'http://jp.xvideos.com/video10683385/', $movies_url[0]
        );
    }


    /**
     * @test
     * @medium
     * @group eromon-get-movies-url3
     * @group eromon
     */
    public function 動画へのリンクを取得する3 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(
            'http://jp.xhamster.com/movies/1123387/i_become_a_stalker_in_the_home.html',
            $movies_url[0]
        );
    }


    /**
     * @test
     * @medium
     * @group eromon-get-movies-url4
     * @group eromon
     */
    public function 動画へのリンクを取得する4 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[3]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));
    }
}

