<?php


use Midnight\Crawler\Plugin\TestData\YouskbeTestData;

class YouskbeTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var YouskbeTestData
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new YouskbeTestData();
    }


    /**
     * @test
     * @group youskbe-test
     * @group youskbe-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/youskbe.xml', $rss_path);
    }


    /**
     * @test
     * @group youskbe-test
     * @group youskbe-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
