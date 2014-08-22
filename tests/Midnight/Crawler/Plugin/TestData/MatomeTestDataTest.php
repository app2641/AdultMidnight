<?php


use Midnight\Crawler\Plugin\TestData\MatomeTestData;

class MatomeTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MatomeTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new MatomeTestData();
    }


    /**
     * @test
     * @group matome-test
     * @group matome-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/matome.xml', $rss_path);
    }


    /**
     * @test
     * @group matome-test
     * @group matome-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(1, count($html_paths));
    }
}
