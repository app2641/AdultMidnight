<?php


use Midnight\Crawler\Plugin\TestData\DownloadTestData;

class DownloadTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var DownloadTestData
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new DownloadTestData();
    }


    /**
     * @test
     * @group download-test
     * @group download-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/download.xml', $rss_path);
    }


    /**
     * @test
     * @group download-test
     * @group download-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
