<?php


use Midnight\Crawler\Plugin\TestData\AdultVideoTestData;

class AdultVideoTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AdultVideoTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new AdultVideoTestData();
    }


    /**
     * @test
     * @group adultvideo-test
     * @group adultvideo-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/adult-video.xml', $rss_path);
    }


    /**
     * @test
     * @group adultvideo-test
     * @group adultvideo-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(4, count($html_paths));
    }
}
