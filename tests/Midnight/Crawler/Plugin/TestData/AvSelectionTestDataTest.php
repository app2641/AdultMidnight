<?php


use Midnight\Crawler\Plugin\TestData\AvSelectionTestData;

class AvSelectionTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AvSelectionTestData
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new AvSelectionTestData();
    }


    /**
     * @test
     * @group selection-test
     * @group selection-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/av-selection.xml', $rss_path);
    }


    /**
     * @test
     * @group selection-test
     * @group selection-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
