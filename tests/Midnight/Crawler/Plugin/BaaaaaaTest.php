<?php


use Midnight\Crawler\Plugin\Baaaaaa;

class BaaaaaaTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Baaaaaa
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
        'baaaaaa/blog-entry-3883.html',
        'baaaaaa/blog-entry-3879.html'
    );


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->plugin = new Baaaaaa();
        $this->xml_data = file_get_contents(ROOT.'/data/fixtures/rss/baaaaaa.xml');
    }


    /**
     * @test
     * @group baaaaaa
     * @group baaaaaa-fetch-rss
     */
    public function RSSを取得する ()
    {
        $dom = $this->plugin->fetchRss($this->xml_data);
        $this->assertInstanceOf('DOMDocument', $dom);
    }


    /**
     * @test
     * @group baaaaaa
     * @group baaaaaa-get-entries
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
     * @group baaaaaa
     * @group baaaaaa-get-entry-url
     */
    public function エントリのURLを取得する ()
    {
        $dom     = $this->plugin->fetchRss($this->xml_data);
        $entries = $this->plugin->getEntries($dom);

        $url = $this->plugin->getEntryUrl($entries->item(0));
        $this->assertTrue(is_string($url));
        $this->assertEquals('http://baaaaaa.blog.fc2.com/blog-entry-3883.html', $url);
    }


    /**
     * @test
     * @group baaaaaa-get-entry-date
     * @group baaaaaa
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
     * @group baaaaaa-fetch-html
     * @group baaaaaa
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
     * @group baaaaaa-get-title
     * @group baaaaaa
     */
    public function エントリのタイトルを取得する ()
    {
        $dry_run = true;
        $html  = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $title = $this->plugin->getEntryTitle($html);

        $this->assertEquals('激カワ美少女にオチ○チン突っ込みながらインタビュー', $title);
    }


    /**
     * @test
     * @medium
     * @group baaaaaa-get-eyecatch-url
     * @group baaaaaa
     */
    public function アイキャッチ画像のURLを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $img_url = $this->plugin->getEyeCatchUrl($html);

        $this->assertEquals('http://blog-imgs-64.fc2.com/b/a/a/baaaaaa/3947.jpg', $img_url);
    }


    /**
     * @test
     * @medium
     * @group baaaaaa-get-movies-url
     * @group baaaaaa
     */
    public function 動画へのリンクを取得する ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[0], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals('http://video.fc2.com/content/20130827LybYzuyu', $movies_url[0]);
    }


    /**
     * @test
     * @medium
     * @group baaaaaa-get-advertisement
     * @group baaaaaa
     **/
    public function 広告ページで動画リンクを取得する場合 ()
    {
        $dry_run = true;
        $html    = $this->plugin->fetchHtml($this->html_paths[1], $dry_run);
        $movies_url = $this->plugin->getMoviesUrl($html);

        $this->assertTrue(is_array($movies_url));
        $this->assertEquals(0, count($movies_url));
    }
}

