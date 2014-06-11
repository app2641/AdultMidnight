<?php


use Midnight\Crawler\Plugin\Youskbe;

class YouskbeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Youskbe
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
        'youskbe/11_161548.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new Youskbe;
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/youskbe.xml');
    }


    /**
     * @test
     * @group youskbe
     * @group youskbe-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group youskbe
     * @group youskbe-get-entries
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
     * @group youskbe
     * @group youskbe-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://www.youskbe.com/blog/archives/2014/06/11_161548.php', $url);
    }


    /**
     * @test
     * @group youskbe-get-entry-date
     * @group youskbe
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-06-11', $date);
    }


    /**
     * @test
     * @medium
     * @group youskbe-fetch-html
     * @group youskbe
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
     * @group youskbe-get-title
     * @group youskbe
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【愛内希】萌えるコスプレ作品！【XVideos】', $title);
    }


    /**
     * @test
     * @medium
     * @group youskbe-get-eyecatch-url
     * @group youskbe
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://www.youskbe.com/img2/12/07/281121.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group youskbe-get-movies-url
     * @group youskbe
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://www.xvideos.com/video7953646/', $movies_url[0]);
        $this->assertEquals('http://www.xvideos.com/video7953677/', $movies_url[1]);
    }
}

