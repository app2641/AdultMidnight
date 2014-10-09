<?php

use Midnight\Crawler\Plugin\TestData\HentaiAnimeTestData;

class HentaiAnimeTestDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var HentaiAnimeTestDatt
     **/
    private $test_data;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->test_data = new HentaiAnimeTestData();
    }


    /**
     * @test
     * @group hentai-test
     * @group hentai-test-get-rss-path
     */
    public function テスト用RSSデータのパスを取得する ()
    {
        $rss_path = $this->test_data->getRssPath();
        $this->assertEquals(ROOT.'/data/fixtures/rss/hentai-anime.xml', $rss_path);
    }


    /**
     * @test
     * @group hentai-test
     * @group hentai-test-get-html-paths
     */
    public function テスト用Htmlデータのパスを取得する ()
    {
        $html_paths = $this->test_data->getHtmlPaths();
        $this->assertEquals(3, count($html_paths));
    }
}
