<?php


use Midnight\Crawler\Plugin\Epusta;
use Midnight\Crawler\Plugin\TestData\EpustaTestData;

class EpustaTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Epusta
     **/
    private $plugin;


    /**
     * @var EpustaTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new EpustaTestData();

        $this->plugin = new Epusta();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    RSSを取得出来ませんでした
     * @group epusta
     * @group epusta-valid-rss
     */
    public function RSSが取得出来なかった場合 ()
    {
        $test_data = $this->getMock(
            'Midnight\Crawler\Plugin\TestData\EpustaTestData',
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
     * @group epusta
     * @group epusta-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group epusta
     * @group epusta-get-entries
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
     * @group epusta
     * @group epusta-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://av0yourfilehost.blog35.fc2.com/blog-entry-47054.html', $url);
    }


    /**
     * @test
     * @group epusta-get-entry-date
     * @group epusta
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-07-08', $date);
    }


    /**
     * @test
     * @medium
     * @group epusta-fetch-html
     * @group epusta
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    タイトルを取得出来ませんでした
     * @group epusta-not-get-title
     * @group epusta
     */
    public function エントリのタイトルを取得出来なかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $this->plugin->getEntryTitle($html);
    }


    /**
     * @test
     * @medium
     * @group epusta-get-title
     * @group epusta
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('[長澤あずさ]巨乳嫁が入れて欲しがるまで攻め続けた義父[xvideos]', $title);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    アイキャッチを取得出来ませんでした
     * @group epusta-not-get-eyecatch-img-el
     * @group epusta
     */
    public function アイキャッチの画像要素が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    src属性が見つかりませんでした
     * @group epusta-not-get-img-src
     * @group epusta
     */
    public function src属性が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @medium
     * @group epusta-get-eyecatch-url
     * @group epusta
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-70.fc2.com/a/v/0/av0yourfilehost/0707es5.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group epusta-get-movies-url
     * @group epusta
     */
    public function 動画へのリンクを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths());
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://flashservice.xvideos.com/embedframe/8384715', $movies_url[0]);
        $this->assertEquals('http://flashservice.xvideos.com/embedframe/8384744', $movies_url[1]);
    }
}
