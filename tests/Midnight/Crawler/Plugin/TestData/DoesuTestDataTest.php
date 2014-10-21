<?php


use Midnight\Crawler\Plugin\TestData\DoesuTestData;

class DoesTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var DoesuTestData
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new DoesuTestData();
    }


    /**
     * @test
     * @group doesu-test
     * @group doesu-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/doesu.xml', $rss_path);
    }


    /**
     * @test
     * @group doesu-test
     * @group doesu-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
