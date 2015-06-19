<?php


use Midnight\Crawler\Plugin\EroAnime,
    Midnight\Crawler\Plugin\TestData\EroAnimeTestData;

class EroAnimeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EroAnime
     **/
    private $plugin;


    /**
     * @var EroAnimeTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new EroAnimeTestData();

        $this->plugin = new EroAnime();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    RSSを取得出来ませんでした
     * @group eroanime
     * @group eroanime-valid-rss
     */
    public function RSSが取得出来なかった場合 ()
    {
        $test_data = $this->getMock(
            'Midnight\Crawler\Plugin\TestData\EroAnimeTestData',
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
     * @group eroanime
     * @group eroanime-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group eroanime
     * @group eroanime-get-entries
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
     * @group eroanime
     * @group eroanime-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertEquals('http://anime-erodouga.com/schoolgirls/boin.html', $url);
    }


    /**
     * @test
     * @group eroanime-get-entry-date
     * @group eroanime
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertEquals('2015-06-12', $date);
    }


    /**
     * @test
     * @medium
     * @group eroanime-fetch-html
     * @group eroanime
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group eroanime-get-title
     * @group eroanime
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('boin レクチャー', $title);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    タイトルを取得出来ませんでした
     * @group eroanime-not-get-title
     * @group eroanime
     */
    public function エントリのタイトルを取得出来なかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $this->plugin->getEntryTitle($html);
    }


    /**
     * @test
     * @medium
     * @group eroanime-get-eyecatch-url
     * @group eroanime
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://image.anime-erodouga.com/material/kousin/0558.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    アイキャッチを取得出来ませんでした
     * @group eroanime-not-get-eyecatch-img-el
     * @group eroanime
     */
    public function アイキャッチの画像要素が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    src属性が見つかりませんでした
     * @group eroanime-not-get-img-src
     * @group eroanime
     */
    public function src属性が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[3]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @medium
     * @group eroanime-get-movies-url1
     * @group eroanime
     */
    public function 動画へのリンクを取得する1 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(8, count($movies_url));
    }


    /**
     * @test
     * @medium
     * @group eroanime-get-movies-url2
     * @group eroanime
     */
    public function 動画へのリンクを取得する2 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(15, count($movies_url));
    }
}

