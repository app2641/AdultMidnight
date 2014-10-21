<?php


use Midnight\Crawler\Plugin\TestData\ShikosenTestData;

class ShikosenTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ShikosenTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new ShikosenTestData();
    }


    /**
     * @test
     * @group shikosen-test
     * @group shikosen-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/shikosen.xml', $rss_path);
    }


    /**
     * @test
     * @group shikosen-test
     * @group shikosen-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
