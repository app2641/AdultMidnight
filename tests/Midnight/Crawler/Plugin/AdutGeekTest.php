<?php


use Midnight\Crawler\Plugin\AdultGeek;

class AdultGeekTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AdultGeek
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
        'adult-geek/post_5459.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new AdultGeek;
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/adult-geek.xml');
    }


    /**
     * @test
     * @group geek
     * @group geek-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group geek
     * @group geek-get-entries
     */
    public function コンテンツ要素を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);
        $this->assertInstanceOf('DOMNodeList', $entries);
    }


    /**
     * @test
     * @group geek
     * @group geek-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
    }


    /**
     * @test
     * @group geek-get-entry-date
     * @group geek
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
    }


    /**
     * @test
     * @medium
     * @group geek-fetch-html
     * @group geek
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
     * @group geek-get-title
     * @group geek
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【エロ動画】 真夏の海！水着ギャルをミラー号に乗せて猥褻本番マッサージ企画！', $title);
    }


    /**
     * @test
     * @medium
     * @group geek-get-eyecatch-url
     * @group geek
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://www.adultgeek.net/upimg/1406/mmgouhiyake.jpg', $img_url);
    }
}

