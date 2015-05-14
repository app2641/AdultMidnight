<?php


use Garnet\Container,
    Midnight\Factory\CrawlerPluginTestDataFactory;

class IchizenTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var IchizenTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $container = new Container(new CrawlerPluginTestDataFactory);
        $this->test_data = $container->get('Ichizen');
    }


    /**
     * @test
     * @group ichizen-test
     * @group ichizen-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/ichizen.xml', $rss_path);
    }


    /**
     * @test
     * @group ichizen-test
     * @group ichizen-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(4, count($html_paths));
    }
}
