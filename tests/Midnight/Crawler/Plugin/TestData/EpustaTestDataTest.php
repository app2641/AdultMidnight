<?php


use Midnight\Crawler\Plugin\TestData\EpustaTestData;

class EpustaTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EpustaTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new EpustaTestData();
    }


    /**
     * @test
     * @group epusta-test
     * @group epusta-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/epusta.xml', $rss_path);
    }


    /**
     * @test
     * @group epusta-test
     * @group epusta-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(1, count($html_paths));
    }
}
