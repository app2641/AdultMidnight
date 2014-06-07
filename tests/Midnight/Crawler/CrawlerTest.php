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
        $this->plugin = $this->getMock('Midnight\Crawler\Plugin\AdultGeek');
    }


    /**
     * @test
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
     * @group crawler
     * @group crawler-crawl
     */
    public function 正常な処理 ()
    {
        $this->crawler->setPlugin($this->plugin);
        $result = $this->crawler->crawl();

        $this->assertTrue(is_array($result));
    }
}
