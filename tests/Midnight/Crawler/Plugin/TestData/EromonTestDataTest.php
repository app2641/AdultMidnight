<?php


use Garnet\Container,
    Midnight\Factory\CrawlerPluginTestDataFactory;

class EromonTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EromonTestDatt
     **/
    private $test_data;

    /**
     * @return void
     **/
    public function setUp ()
    {
        $container = new Container(new CrawlerPluginTestDataFactory);
        $this->test_data = $container->get('Eromon');
    }

    /**
     * @test
     * @group eromon-test
     * @group eromon-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/eromon.xml', $rss_path);
    }

    /**
     * @test
     * @group eromon-test
     * @group eromon-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(6, count($html_paths));
    }
}
