<?php


use Midnight\Crawler\Plugin\TestData\AdultGeekTestData;

class AdultGeekTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AdultGeekTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new AdultGeekTestData();
    }


    /**
     * @test
     * @group geek-test
     * @group geek-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/adult-geek.xml', $rss_path);
    }


    /**
     * @test
     * @group geek-test
     * @group geek-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(1, count($html_paths));
    }
}
