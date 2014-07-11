<?php


use Midnight\Crawler\Plugin\Matome;

class MatomeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Matome
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
        'matome/blog-entry-3641.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new Matome();
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/matome.xml');
    }


    /**
     * @test
     * @group matome
     * @group matome-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group matome
     * @group matome-get-entries
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
     * @group matome
     * @group matome-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://erodougaavdouga.blog.fc2.com/blog-entry-3641.html', $url);
    }


    /**
     * @test
     * @group matome-get-entry-date
     * @group matome
     */
    public function エントリの日付を取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $date = $this->plugin->getEntryDate($entries->item(0));
        $this->assertTrue(is_string($date));
        $this->assertEquals('2014-07-10', $date);
    }


    /**
     * @test
     * @medium
     * @group matome-fetch-html
     * @group matome
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
     * @group matome-get-title
     * @group matome
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('颯希真衣と二人っきりでお泊りデート', $title);
    }


    /**
     * @test
     * @medium
     * @group matome-get-eyecatch-url
     * @group matome
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-68-origin.fc2.com/e/r/o/erodougaavdouga/0709av6.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group matome-get-movies-url
     * @group matome
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://jp.xvideos.com/video8236196', $movies_url[0]);
        $this->assertEquals('http://jp.xvideos.com/video8236299', $movies_url[1]);
    }
}
