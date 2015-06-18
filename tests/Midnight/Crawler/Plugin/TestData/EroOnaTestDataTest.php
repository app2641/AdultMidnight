<?php


use Garnet\Container,
    Midnight\Factory\CrawlerPluginTestDataFactory;

class EroOnaTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EroOnaTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $container = new Container(new CrawlerPluginTestDataFactory);
        $this->test_data = $container->get('EroOna');
    }


    /**
     * @test
     * @group eroona-test
     * @group eroona-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/eroona.xml', $rss_path);
    }


    /**
     * @test
     * @group eroona-test
     * @group eroona-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(5, count($html_paths));
    }
}
