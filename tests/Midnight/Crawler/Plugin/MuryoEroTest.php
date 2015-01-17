<?php


use Midnight\Crawler\Plugin\MuryoEro,
    Midnight\Crawler\Plugin\TestData\MuryoEroTestData;

class MuryoEroTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MuryoEro
     **/
    private $plugin;


    /**
     * @var MuryoEroTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new MuryoEroTestData();

        $this->plugin = new MuryoEro();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    RSSを取得出来ませんでした
     * @group muryoero
     * @group muryoero-valid-rss
     */
    public function RSSが取得出来なかった場合 ()
    {
        $test_data = $this->getMock(
            'Midnight\Crawler\Plugin\TestData\MuryoEroTestData',
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
     * @group muryoero
     * @group muryoero-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group muryoero
     * @group muryoero-get-entries
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
     * @group muryoero
     * @group muryoero-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertEquals('http://muryouerodouga.net/archives/15535', $url);
    }


    /**
     * @test
     * @group muryoero-get-entry-date
     * @group muryoero
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertEquals('2015-01-16', $date);
    }


    /**
     * @test
     * @medium
     * @group muryoero-fetch-html
     * @group muryoero
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group muryoero-get-title
     * @group muryoero
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('エロオヤジにローションまみれにされて性感マッサージを受ける女！', $title);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    タイトルを取得出来ませんでした
     * @group muryoero-not-get-title
     * @group muryoero
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
     * @group muryoero-get-eyecatch-url
     * @group muryoero
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://img-l3.xvideos.com/videos/thumbslll/7f/71/52/7f71529706fda7432fc849cf0f1357b7/7f71529706fda7432fc849cf0f1357b7.16.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    アイキャッチを取得出来ませんでした
     * @group muryoero-not-get-eyecatch-img-el
     * @group muryoero
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
     * @group muryoero-not-get-img-src
     * @group muryoero
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
     * @group muryoero-get-movies-url1
     * @group muryoero
     */
    public function 動画へのリンクを取得する1 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video9401271/', $movies_url[0]);
        $this->assertEquals('http://jp.xvideos.com/video9401309/', $movies_url[1]);
    }
}

