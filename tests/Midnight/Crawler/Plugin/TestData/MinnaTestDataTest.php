<?php


use Midnight\Crawler\Plugin\TestData\MinnaTestData;

class MinnaTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MinnaTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new MinnaTestData();
    }


    /**
     * @test
     * @group minna-test
     * @group minna-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/minna.xml', $rss_path);
    }


    /**
     * @test
     * @group minna-test
     * @group minna-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
