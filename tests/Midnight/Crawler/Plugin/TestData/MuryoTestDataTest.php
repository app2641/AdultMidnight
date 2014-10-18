<?php


use Midnight\Crawler\Plugin\TestData\MuryoTestData;

class MuryoTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MuryoTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new MuryoTestData();
    }


    /**
     * @test
     * @group muryo-test
     * @group muryo-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/muryo.xml', $rss_path);
    }


    /**
     * @test
     * @group muryo-test
     * @group muryo-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(5, count($html_paths));
    }
}
