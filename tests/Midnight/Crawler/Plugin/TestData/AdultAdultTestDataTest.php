<?php


use Midnight\Crawler\Plugin\TestData\AdultAdultTestData;

class AdultAdultTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AdultAdultTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new AdultAdultTestData();
    }


    /**
     * @test
     * @group adult-test
     * @group adult-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/adult-adult.xml', $rss_path);
    }


    /**
     * @test
     * @group adult-test
     * @group adult-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(2, count($html_paths));
    }
}
