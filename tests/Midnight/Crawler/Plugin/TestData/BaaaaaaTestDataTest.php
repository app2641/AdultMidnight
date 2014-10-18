<?php


use Midnight\Crawler\Plugin\TestData\BaaaaaaTestData;

class BaaaaaaTestDataTest extends PHPUnit_Framework_TestCase
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
        $this->test_data = new BaaaaaaTestData();
    }


    /**
     * @test
     * @group baaaaaa-test
     * @group baaaaaa-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/baaaaaa.xml', $rss_path);
    }


    /**
     * @test
     * @group baaaaaa-test
     * @group baaaaaa-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(4, count($html_paths));
    }
}
