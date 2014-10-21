<?php


use Midnight\Crawler\Plugin\AdultAdult,
    Midnight\Crawler\Plugin\TestData\AdultAdultTestData;

class AdultAdultTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AdultAdult
     **/
    private $plugin;


    /**
     * @var AdultAdultTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new AdultAdultTestData();

        $this->plugin = new AdultAdult();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    RSSを取得出来ませんでした
     * @group adult
     * @group adult-valid-rss
     */
    public function RSSが取得出来なかった場合 ()
    {
        $test_data = $this->getMock(
            'Midnight\Crawler\Plugin\TestData\AdultAdultTestData',
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
     * @group adult
     * @group adult-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group adult
     * @group adult-get-entries
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
     * @group adult
     * @group adult-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://tifer2.blog86.fc2.com/blog-entry-19719.html', $url);
    }


    /**
     * @test
     * @group adult-get-entry-date
     * @group adult
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-06-17', $date);
    }


    /**
     * @test
     * @medium
     * @group adult-fetch-html
     * @group adult
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group adult-get-title
     * @group adult
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【無修正】素顔の巨乳AV嬢とプライベート感たっぷりな主観中出しSEX♪', $title);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    タイトルを取得出来ませんでした
     * @group adult-not-get-title
     * @group adult
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
     * @group adult-get-eyecatch-url
     * @group adult
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-64-origin.fc2.com/t/i/f/tifer2/20140616083556451.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    アイキャッチを取得出来ませんでした
     * @group adult-not-get-eyecatch-img-el
     * @group adult
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
     * @group adult-not-get-img-src
     * @group adult
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
     * @group adult-get-movies-url
     * @group adult
     */
    public function 動画へのリンクを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://javmon.com/embed/96696-javmon.com.html', $movies_url[0]);
        $this->assertEquals('http://netu.tv/watch_video.php?v=O91HXO4MK8OR', $movies_url[1]);
        $this->assertEquals('http://www.videowood.tv/embed/sbz', $movies_url[2]);
        $this->assertEquals('http://www.divxstage.eu/video/2e1d9a888291d', $movies_url[3]);
    }


    /**
     * @test
     * @medium
     * @group adult-get-advertisement
     * @group adult
     **/
    public function 広告ページで動画リンクを取得する場合 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));
    }
}

