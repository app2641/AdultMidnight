<?php


use Midnight\Crawler\Plugin\TestData\EroEroTestData;

class EroEroTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EroEroTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new EroEroTestData();
    }


    /**
     * @test
     * @group ero-test
     * @group ero-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/ero-ero.xml', $rss_path);
    }


    /**
     * @test
     * @group ero-test
     * @group ero-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(7, count($html_paths));
    }
}
