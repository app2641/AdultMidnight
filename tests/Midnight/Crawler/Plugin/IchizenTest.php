<?php


use Midnight\Crawler\Plugin\Ichizen,
    Midnight\Crawler\Plugin\TestData\IchizenTestData;

class IchizenTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Ichizen
     **/
    private $plugin;


    /**
     * @var IchizenTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new IchizenTestData();

        $this->plugin = new Ichizen();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @large
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    RSSを取得出来ませんでした
     * @group ichizen
     * @group ichizen-valid-rss
     */
    public function RSSが取得出来なかった場合 ()
    {
        $test_data = $this->getMock(
            'Midnight\Crawler\Plugin\TestData\IchizenTestData',
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
     * @large
     * @group ichizen
     * @group ichizen-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @large
     * @group ichizen
     * @group ichizen-get-entries
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
     * @large
     * @group ichizen
     * @group ichizen-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertEquals('http://eroichizen.com/blog-entry-3847.html', $url);
    }


    /**
     * @test
     * @large
     * @group ichizen-get-entry-date
     * @group ichizen
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertEquals('2015-05-07', $date);
    }


    /**
     * @test
     * @large
     * @medium
     * @group ichizen-fetch-html
     * @group ichizen
     */
    public function HTMLを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @large
     * @medium
     * @group ichizen-get-title
     * @group ichizen
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('南国産ぷるるん巨乳介護ギャルがジジイに騙されてヤラれ放題ｗ│ティア【長時間】', $title);
    }


    /**
     * @test
     * @large
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    タイトルを取得出来ませんでした
     * @group ichizen-not-get-title
     * @group ichizen
     */
    public function エントリのタイトルを取得出来なかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $this->plugin->getEntryTitle($html);
    }


    /**
     * @test
     * @large
     * @medium
     * @group ichizen-get-eyecatch-url
     * @group ichizen
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-75.fc2.com/m/o/r/morodora/20150505235437cb1.jpg', $img_url);
    }


    /**
     * @test
     * @large
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    アイキャッチを取得出来ませんでした
     * @group ichizen-not-get-eyecatch-img-el
     * @group ichizen
     */
    public function アイキャッチの画像要素が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @large
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    src属性が見つかりませんでした
     * @group ichizen-not-get-img-src
     * @group ichizen
     */
    public function src属性が見つからなかった場合 ()
    {
        $this->setExpectedException('Midnight\Utility\CrawlerException');

        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[3]);
        $this->plugin->getEyeCatchUrl($html);
    }


    /**
     * @test
     * @large
     * @medium
     * @group ichizen-get-movies-url1
     * @group ichizen
     */
    public function 動画へのリンクを取得する1 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(
            'http://jp.xhamster.com/movies/3223556/japanese_old_mans_caregiver_tia_mrbonham_part_1.html',
            $movies_url[0]
        );
        $this->assertEquals(
            'http://jp.xhamster.com/movies/3224178/japanese_old_mans_caregiver_tia_mrbonham_part_2.html',
            $movies_url[1]
        );
    }


    /**
     * @test
     * @large
     * @medium
     * @group ichizen-get-movies-url2
     * @group ichizen
     */
    public function 動画へのリンクを取得する2 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));
    }
}

