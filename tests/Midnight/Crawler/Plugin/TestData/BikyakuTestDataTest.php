<?php


use Midnight\Crawler\Plugin\TestData\BikyakuTestData;

class BikyakuTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
r    * @var BikyakuTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new BikyakuTestData();
    }


    /**
     * @test
     * @group bikyaku-test
     * @group bikyaku-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/bikyaku.xml', $rss_path);
    }


    /**
     * @test
     * @group bikyaku-test
     * @group bikyaku-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(2, count($html_paths));
    }
}
