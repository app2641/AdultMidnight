<?php


use Midnight\Crawler\Crawler;
use Garnet\Container,
    Midnight\Factory\CrawlerPluginFactory;

class CrawlerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Crawler
     **/
    private $crawler;


    /**
     * @var PluginInterface
     **/
    private $plugin;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->crawler = new Crawler();


        $rss = new DOMDocument('1.0', 'UTF-8');
        $rss->load(ROOT.'/data/fixtures/rss/adult-geek.xml');

        $this->plugin = $this->getMock('Midnight\Crawler\Plugin\AdultGeek');
        $this->plugin->expects($this->any())
            ->method('fetchRss')
            ->will($this->returnValue($rss));

        $entries = $rss->getElementsByTagName('item');
        $this->plugin->expects($this->any())
            ->method('getEntries')
            ->will($this->returnValue($entries));

        $date = date('Y-m-d');
        $this->plugin->expects($this->any())
            ->method('getEntryDate')
            ->will($this->returnValue($date));

        $url = 'http://www.adultgeek.net/2014/06/post_5459.html';
        $this->plugin->expects($this->any())
            ->method('getEntryUrl')
            ->will($this->returnValue($url));

        require_once ROOT.'/src/Library/SimpleHtmlDomParser/simple_html_dom.php';
        $html = str_get_html(file_get_contents(ROOT.'/data/fixtures/html/adult-geek/post_5459.html'));
        $this->plugin->expects($this->any())
            ->method('fetchHtml')
            ->will($this->returnValue($html));

        $this->plugin->expects($this->any())
            ->method('getEntryTitle')
            ->will($this->returnValue('【エロ動画】 真夏の海！水着ギャルをミラー号に乗せて猥褻本番マッサージ企画！'));

        $this->plugin->expects($this->any())
            ->method('getEyeCatchUrl')
            ->will($this->returnValue('http://www.adultgeek.net/upimg/1406/mmgouhiyake.jpg'));

        $this->plugin->expects($this->any())
            ->method('getMoviesUrl')
            ->will($this->returnValue(array('http://vid.bz/watch/yW8Xp5')));
    }


    /**
     * @test
     * @medium
     * @expectedException           Exception
     * @expectedExceptionMessage    プラグインが指定されていません
     * @group crawler
     * @group crawler-not-set-plugin
     */
    public function プラグインが指定されていない場合 ()
    {
        $this->crawler->crawl();
    }


    /**
     * @test
     * @medium
     * @group crawler
     * @group crawler-crawl
     */
    public function 正常な処理 ()
    {
        $this->crawler->setPlugin($this->plugin);
        $result = $this->crawler->crawl();

        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result[0]));
        $this->assertArrayHasKey('title', $result[0]);
        $this->assertArrayHasKey('eyecatch', $result[0]);
        $this->assertArrayHasKey('movies', $result[0]);
    }
}
