<?php


use Midnight\Crawler\Plugin\Download;

class DownloadTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Download
     **/
    private $plugin;


    /**
     * RSSデータ
     *
     * @var string
     **/
    private $xml_data;


    /**
     * HTMLテストデータのパス
     *
     * @var array
     **/
    private $html_paths = array(
        'download/60188.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new Download();
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/download.xml');
    }


    /**
     * @test
     * @group download
     * @group download-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group download
     * @group download-get-entries
     */
    public function コンテンツ要素を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);
        $this->assertInstanceOf('DOMNodeList', $entries);
        $this->assertFalse(is_null($entries->item(0)));
    }


    /**
     * @test
     * @group download
     * @group download-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://xvideos-field.com/archives/60188', $url);
    }


    /**
     * @test
     * @group download-get-entry-date
     * @group download
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-07-03', $date);
    }


    /**
     * @test
     * @medium
     * @group download-fetch-html
     * @group download
     */
    public function HTMLを取得する ()
    {
        $dry_run = true;
        $html = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);

        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @medium
     * @group download-get-title
     * @group download
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【美藤れん】イイ女たちの美しきレズPLAY DUAL BOX 駆け引きの無しの愛撫で淫れ合う真のエロス', $title);
    }


    /**
     * @test
     * @medium
     * @group download-get-eyecatch-url
     * @group download
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals(
            'http://img100-937.xvideos.com/videos/thumbslll/34/f7/0c/'.
            '34f70c8ec830ecbfa81496c54dd6d0f2/34f70c8ec830ecbfa81496c54dd6d0f2.2.jpg', $img_url
        );
    }


    /**
     * @test
     * @medium
     * @group download-get-movies-url
     * @group download
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video1764937', $movies_url[0]);
        $this->assertEquals('http://jp.xvideos.com/video1764995', $movies_url[1]);
    }
}
