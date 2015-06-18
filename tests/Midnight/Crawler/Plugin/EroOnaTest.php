<?php


use Midnight\Crawler\Plugin\EroOna,
    Midnight\Crawler\Plugin\TestData\EroOnaTestData;

class EroOnaTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EroOna
     **/
    private $plugin;


    /**
     * @var EroOnaTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new EroOnaTestData();

        $this->plugin = new EroOna();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    RSSを取得出来ませんでした
     * @group eroona
     * @group eroona-valid-rss
     */
    public function RSSが取得出来なかった場合 ()
    {
        $test_data = $this->getMock(
            'Midnight\Crawler\Plugin\TestData\EroOnaTestData',
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
     * @group eroona
     * @group eroona-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group eroona
     * @group eroona-get-entries
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
     * @group eroona
     * @group eroona-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertEquals('http://eroona07.blog.fc2.com/blog-entry-1606.html', $url);
    }


    /**
     * @test
     * @group eroona-get-entry-date
     * @group eroona
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertEquals('2015-06-17', $date);
    }


    /**
     * @test
     * @medium
     * @group eroona-fetch-html
     * @group eroona
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group eroona-get-title
     * @group eroona
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('男性が膣奥で中出し射精する際に女性も精液が膣に当たる刺激で昇天する', $title);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    タイトルを取得出来ませんでした
     * @group eroona-not-get-title
     * @group eroona
     */
    public function エントリのタイトルを取得出来なかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[3]);
        $this->plugin->getEntryTitle($html);
    }


    /**
     * @test
     * @medium
     * @group eroona-get-eyecatch-url
     * @group eroona
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-75-origin.fc2.com/e/r/o/eroona07/2015061700302908e.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    アイキャッチを取得出来ませんでした
     * @group eroona-not-get-eyecatch-img-el
     * @group eroona
     */
    public function アイキャッチの画像要素が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[3]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    src属性が見つかりませんでした
     * @group eroona-not-get-img-src
     * @group eroona
     */
    public function src属性が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[4]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @medium
     * @group eroona-get-movies-url1
     * @group eroona
     */
    public function 動画へのリンクを取得する1 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://javynow.com/video.php?id=MzYzMTYz&n=1&s=1&h=700', $movies_url[0]);
    }


    /**
     * @test
     * @medium
     * @group eroona-get-movies-url2
     * @group eroona
     */
    public function 動画へのリンクを取得する2 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://xhamster.com/xembed.php?video=3901696', $movies_url[0]);
    }


    /**
     * @test
     * @medium
     * @group eroona-get-movies-url3
     * @group eroona
     */
    public function 動画へのリンクを取得する3 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://javynow.com/video.php?id=MzU4OTA3&n=1&s=1&h=700', $movies_url[0]);
        $this->assertEquals('http://javynow.com/video.php?id=MzU4OTA3&n=2&s=1&h=700', $movies_url[1]);
        $this->assertEquals('http://javynow.com/video.php?id=MzU4OTA3&n=3&s=1&h=700', $movies_url[2]);
    }
}

