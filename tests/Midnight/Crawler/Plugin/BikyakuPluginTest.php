<?php


use Midnight\Crawler\Plugin\Bikyaku;

class BikyakuTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Bikyaku
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
        'bikyaku/blog-entry-1957.html',
        'bikyaku/blog-entry-1954.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new Bikyaku();
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/bikyaku.xml');
    }


    /**
     * @test
     * @group bikyaku
     * @group bikyaku-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group bikyaku
     * @group bikyaku-get-entries
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
     * @group bikyaku
     * @group bikyaku-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://bikyakukiss.blog40.fc2.com/blog-entry-1957.html', $url);
    }


    /**
     * @test
     * @group bikyaku-get-entry-date
     * @group bikyaku
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-06-30', $date);
    }


    /**
     * @test
     * @medium
     * @group bikyaku-fetch-html
     * @group bikyaku
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
     * @group bikyaku-get-title
     * @group bikyaku
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('ミニスカ黒パンストお姉さん達に蒸れた匂いを嗅がされ足責めされるM男(FC2Adult)', $title);
    }


    /**
     * @test
     * @medium
     * @group bikyaku-get-eyecatch-url
     * @group bikyaku
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-70-origin.fc2.com/b/i/k/bikyakukiss/b20140630_2_1.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group bikyaku-get-movies-url
     * @group bikyaku
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://video.fc2.com/ja/a/content/20121211fMQyW6NQ/', $movies_url[0]);


        $html = $this->plugin->fetchHtml($this->html_paths[1], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));
    }
}
