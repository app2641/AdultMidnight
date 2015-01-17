<?php


use Garnet\Container,
    Midnight\Factory\CrawlerPluginTestDataFactory;

class MuryoEroTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MuryoEroTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $container = new Container(new CrawlerPluginTestDataFactory);
        $this->test_data = $container->get('MuryoEro');
    }


    /**
     * @test
     * @group muryoero-test
     * @group muryoero-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/muryoero.xml', $rss_path);
    }


    /**
     * @test
     * @group muryoero-test
     * @group muryoero-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
