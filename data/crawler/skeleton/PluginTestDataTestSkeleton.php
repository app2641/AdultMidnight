<?php


use Midnight\Crawler\Plugin\TestData\${name}TestData;

class ${name}TestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ${name}TestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new ${name}TestData();
    }


    /**
     * @test
     * @group ${group}-test
     * @group ${group}-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/${group}.xml', $rss_path);
    }


    /**
     * @test
     * @group ${group}-test
     * @group ${group}-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
