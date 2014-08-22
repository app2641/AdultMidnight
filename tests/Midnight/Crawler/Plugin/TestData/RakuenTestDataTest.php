<?php


use Midnight\Crawler\Plugin\TestData\RakuenTestData;

class RakuenTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var RakuenTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new RakuenTestData();
    }


    /**
     * @test
     * @group rakuen-test
     * @group rakuen-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/rakuen.xml', $rss_path);
    }


    /**
     * @test
     * @group rakuen-test
     * @group rakuen-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(1, count($html_paths));
    }
}
