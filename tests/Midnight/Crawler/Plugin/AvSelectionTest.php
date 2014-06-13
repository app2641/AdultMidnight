<?php


use Midnight\Crawler\Plugin\AvSelection;

class AvSelectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AvSelection
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
        'av-selection/38476794.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new AvSelection;
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/av-selection.xml');
    }


    /**
     * @test
     * @group selection
     * @group selection-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group selection
     * @group selection-get-entries
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
     * @group selection
     * @group selection-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://av-selection.net/archives/38476794.html', $url);
    }


    /**
     * @test
     * @group selection-get-entry-date
     * @group selection
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-06-04', $date);
    }


    /**
     * @test
     * @medium
     * @group selection-fetch-html
     * @group selection
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
     * @group selection-get-title
     * @group selection
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('チンコをむさぼるのを一時もやめない、まさに痴女中の痴女のお姉さん【エロ動画】', $title);
    }


    /**
     * @test
     * @medium
     * @group selection-get-eyecatch-url
     * @group selection
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://livedoor.blogimg.jp/avselection/imgs/6/e/6e003a7c.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group selection-get-movies-url
     * @group selection
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video7994012', $movies_url[0]);
        $this->assertEquals('http://jp.xvideos.com/video7994057', $movies_url[1]);
    }
}

