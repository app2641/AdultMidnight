<?php


use Midnight\Crawler\Plugin\HentaiAnime,
    Midnight\Crawler\Plugin\TestData\HentaiAnimeTestData;

class HentaiAnimeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var HentaiAnime
     **/
    private $plugin;


    /**
     * @var HentaiAnimeTestData
     **/
    private $test_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new HentaiAnimeTestData();

        $this->plugin = new HentaiAnime();
        $this->plugin->setTestData($this->test_data);
    }


    /**
     * @test
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    RSSを取得出来ませんでした
     * @group hentai
     * @group hentai-valid-rss
     */
    public function RSSが取得出来なかった場合 ()
    {
        $test_data = $this->getMock(
            'Midnight\Crawler\Plugin\TestData\HentaiAnimeTestData',
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
     * @group hentai
     * @group hentai-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss();
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group hentai
     * @group hentai-get-entries
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
     * @group hentai
     * @group hentai-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://hentaianimechannel.blog.fc2.com/blog-entry-317.html', $url);
    }


    /**
     * @test
     * @group hentai-get-entry-date
     * @group hentai
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss();
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-10-09', $date);
    }


    /**
     * @test
     * @medium
     * @group hentai-fetch-html
     * @group hentai
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
     * @group hentai-not-get-title
     * @group hentai
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
     * @group hentai-get-title
     * @group hentai
     */
    public function エントリのタイトルを取得する ()
    {
        $html  = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【無料エロアニメ】たゆたゆ ＃1', $title);
    }


    /**
     * @test
     * @medium
     * @expectedException           CrawlerException
     * @expectedExceptionMessage    アイキャッチを取得出来ませんでした
     * @group hentai-not-get-eyecatch-img-el
     * @group hentai
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
     * @group hentai-not-get-img-src
     * @group hentai
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
     * @group hentai-get-eyecatch-url
     * @group hentai
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $html    = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-63-origin.fc2.com/h/e/n/hentaianimechannel/20140211141731674.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group hentai-get-movies-url
     * @group hentai
     */
    public function 動画リンクを取得する ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[0]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://www.redtube.com/796351', $movies_url[0]);
        $this->assertEquals('http://www.hentai.to/video/1308/tayu-tayu-ep-01-eng-sub', $movies_url[1]);
        $this->assertEquals('http://hentai.animestigma.com/tayu-tayu-episode-1/', $movies_url[2]);
        $this->assertEquals('http://hentaistream.com/watch/tayu-tayu-episode-01', $movies_url[3]);
        $this->assertEquals('http://hentaigasm.com/tayu-tayu-1-raw/', $movies_url[4]);
        $this->assertEquals('http://hentaigasm.com/tayu-tayu-1-subbed/', $movies_url[5]);
    }


    /**
     * @test
     * @medium
     * @group hentai-get-movies-url2
     * @group hentai
     */
    public function 動画リンクを取得する2 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[1]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://www.pornhub.com/view_video.php?viewkey=891094855', $movies_url[0]);
        $this->assertEquals('http://www.wat.tv/video/machi-gurumi-no-wana-episode-6azkz_5j0br_.html', $movies_url[1]);
        $this->assertEquals(
            'http://www.hentai.to/video/255/machi-gurumi-no-wana-hakudaku-ni-mamireta-shitai-vol-2-eng-sub', $movies_url[2]
        );
        $this->assertEquals(
            'http://hentai.animestigma.com/machi-gurumi-no-wana-hakudaku-ni-mamireta-shitai-episode-2/', $movies_url[3]
        );
        $this->assertEquals('http://hentaistream.com/watch/machi-gurumi-no-wana-episode-02', $movies_url[4]);
    }


    /**
     * @test
     * @medium
     * @group hentai-get-movies-url3
     * @group hentai
     */
    public function 動画リンクを取得する3 ()
    {
        $html = $this->plugin->fetchHtml($this->test_data->getHtmlPaths()[2]);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://www.xvideos.com/video7221606/', $movies_url[0]);
        $this->assertEquals('http://www.drtuber.com/video/86891/cougar-trap-ep-2-final', $movies_url[1]);
        $this->assertEquals(
            'http://myvi.tv/embed/html/ozATb8X10940rYjKtpS62pE9PqQ7rNPcb_MskTNo75D-R7IMrA5Z79ES1He8Waz9h0', $movies_url[2]
        );
        $this->assertEquals(
            'http://myvi.tv/embed/html/oYOrduqTnehrOqUO9FIr_9dFbJhgy78uYIW7ZxABMyGm3aM-dnllAkanXCv2neZdg0', $movies_url[3]
        );
        $this->assertEquals('http://hentai.animestigma.com/the-cougar-trap-episode-2/', $movies_url[4]);
    }
}

