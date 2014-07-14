<?php


use Midnight\Crawler\Plugin\Muryo;

class MuryoTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Muryo
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
        'muryo/71340.html',
        'muryo/71370.html',
        'muryo/71376.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new Muryo();
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/muryo.xml');
    }


    /**
     * @test
     * @group muryo
     * @group muryo-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group muryo
     * @group muryo-get-entries
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
     * @group muryo
     * @group muryo-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://xvideo-jp.com/archives/71376', $url);
    }


    /**
     * @test
     * @group muryo-get-entry-date
     * @group muryo
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-07-13', $date);
    }


    /**
     * @test
     * @large
     * @group muryo-fetch-html
     * @group muryo
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
     * @group muryo-get-title
     * @group muryo
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【素人】ただの友達同士だった大学生が初めて一線を越える瞬間', $title);
    }


    /**
     * @test
     * @large
     * @group muryo-get-eyecatch-url
     * @group muryo 
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://muryouav.avximg.com/2014-07/2014_0713_1100_24.jpg', $img_url);
    }


    /**
     * @test
     * @large
     * @group muryo-get-movies-url
     * @group muryo
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://video.fc2.com/content/20140705SXr6nztN', $movies_url[0]);


        $html = $this->plugin->fetchHtml($this->html_paths[1], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));


        $html = $this->plugin->fetchHtml($this->html_paths[2], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://video.fc2.com/content/20140511JztBN4hB', $movies_url[0]);
    }
}
