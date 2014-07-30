<?php


use Midnight\Crawler\Plugin\Shikosen;

class ShikosenTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Shikosen
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
        'shikosen/36152.html',
        'shikosen/toppage.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new Shikosen();
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/shikosen.xml');
    }


    /**
     * @test
     * @group shikosen
     * @group shikosen-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group shikosen
     * @group shikosen-get-entries
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
     * @group shikosen
     * @group shikosen-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://hikaritube.com/video.php?id=36152', $url);
    }


    /**
     * @test
     * @group shikosen-get-entry-date
     * @group shikosen
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-07-29', $date);
    }


    /**
     * @test
     * @large
     * @group shikosen-fetch-html
     * @group shikosen
     */
    public function HTMLを取得する ()
    {
        $dry_run = true;
        $html = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);

        $this->assertInstanceOf('simple_html_dom', $html);
    }


    /**
     * @test
     * @large
     * @group shikosen-get-title
     * @group shikosen
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('超巨乳お姉ちゃんが集団ブリーフ隊に次から次へとぶっかけられるｗｗｗｗ', $title);
    }


    /**
     * @test
     * @large
     * @group shikosen-get-eyecatch-url
     * @group shikosen
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $this->plugin->entry_url = 'http://hikaritube.com/video.php?id=36152';
        $html    = $this->plugin->fetchHtml($this->html_paths[1], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrlFromTopPage($html);

        $this->assertEquals('http://img100-646.xvideos.com/videos/thumbslll/1e/d9/28/1ed928e9400ac87e900dcbd1327c8630/1ed928e9400ac87e900dcbd1327c8630.16.jpg', $img_url);
    }


    /**
     * @test
     * @large
     * @group shikosen-get-movies-url
     * @group shikosen
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video7985646', $movies_url[0]);
    }
}
