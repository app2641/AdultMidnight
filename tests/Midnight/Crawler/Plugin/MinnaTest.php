<?php


use Midnight\Crawler\Plugin\Minna;

class MinnaTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Minna
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
        'minna/68166962.html',
        'minna/68166006.html',
        'minna/68164648.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new Minna;
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/minna.xml');
    }


    /**
     * @test
     * @group minna
     * @group minna-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group minna
     * @group minna-get-entries
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
     * @group minna
     * @group minna-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertEquals('http://eropeg.net/archives/68166962.html', $url);

        $url = $this->plugin->getEntryUrl($entries->item(1));
        $this->assertEquals('http://eropeg.net/archives/68166006.html', $url);

        $url = $this->plugin->getEntryUrl($entries->item(2));
        $this->assertEquals('http://eropeg.net/archives/68164648.html', $url);
    }


    /**
     * @test
     * @group minna-get-entry-date
     * @group minna
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertEquals('2014-06-21', $date);

        $date = $this->plugin->getEntryDate($entries->item(1));
        $this->assertEquals('2014-06-20', $date);

        $date = $this->plugin->getEntryDate($entries->item(2));
        $this->assertEquals('2014-06-18', $date);
    }


    /**
     * @test
     * @medium
     * @group minna-fetch-html
     * @group minna
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
     * @group minna-get-title
     * @group minna
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('【エロ動画】頭良さそうなメガネっ子がフェラで口内射精', $title);
    }


    /**
     * @test
     * @medium
     * @group minna-get-eyecatch-url
     * @group minna
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://livedoor.blogimg.jp/pinky015/imgs/1/7/1783b919.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group minna-get-movies-url
     * @group minna
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://video.fc2.com/ja/a/content/20140518019MUw9N/', $movies_url[0]);


        $html = $this->plugin->fetchHtml($this->html_paths[1], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://asg.to/contentsPage.html?mcd=lNl25A52tkqoweP2', $movies_url[0]);


        $html = $this->plugin->fetchHtml($this->html_paths[2], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video7440211/', $movies_url[0]);
    }
}

