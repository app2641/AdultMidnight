<?php


use Midnight\Crawler\UriManager;

class UriManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var UriManager
     **/
    private $manager;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->manager = new UriManager();
    }


    /**
     * @test
     * @group uri
     * @group uri-not-url
     **/
    public function 引数がurlでなかった場合 ()
    {
        $url = '(๑´ڡ`๑)';
        $url = $this->manager->resolve($url);

        $this->assertFalse($url);
    }


    /**
     * @test
     * @group uri
     * @group uri-embed-xvideos
     */
    public function xvideosのembed用urlを整形する場合 ()
    {
        $url = 'http://flashservice.xvideos.com/embedframe/7953646';
        $url = $this->manager->resolve($url);

        //$this->assertEquals('http://flashservice.xvideos.com/embedframe/7953646', $url);
        $this->assertEquals('http://jp.xvideos.com/video7953646/', $url);
    }


    /**
     * @test
     * @group uri
     * @group uri-xvideos
     */
    public function 海外向けxvideosのurlを整形する場合 ()
    {
        $url = 'http://www.xvideos.com/video7953646/bmw012';
        $url = $this->manager->resolve($url);

        $this->assertEquals('http://jp.xvideos.com/video7953646/bmw012', $url);
    }


    /**
     * @test
     * @group uri
     * @group uri-asg
     */
    public function asgのurlを整形する場合 ()
    {
        $url = 'http://asg.to?mcd=fogefoge&blog=apppppp';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://asg.to/contentsPage.html?mcd=fogefoge', $url);

        $url = 'http://asg.to/contents/embed?browser=chrome&mcd=barbar';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://asg.to/contentsPage.html?mcd=barbar', $url);

        $url = 'http://asg.to/search?q=piyo';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://asg.to', $url);

        $url = 'http://asg.to/about';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://asg.to', $url);
    }


    /**
     * @test
     * @group uri
     * @group uri-fc2
     */
    public function FC2のurlを整形する場合 ()
    {
        // 正しい動画url
        $url = 'http://video.fc2.com/flv2.swf?i=20130827LybYzuyu&d=2185'.
            '&movie_stop=off&no_progressive=1&otag=1&sj=17&rel=0&tk=TXpNeE1EVXpOVEU9';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://video.fc2.com/content/20130827LybYzuyu', $url);

        // 正しい動画url
        $url = 'http://video.fc2.com/a/content/20141117CSbF4p7a/';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://video.fc2.com/a/content/20141117CSbF4p7a/', $url);

        // 正しい動画url
        $url = 'http://video.fc2.com/ja/a/content/20140518019MUw9N/';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://video.fc2.com/ja/a/content/20140518019MUw9N/', $url);

        // 誤った動画url
        $url = 'http://video.fc2.com/flv2.swf?d31sd=adflkadg';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://video.fc2.com', $url);
    }


    /**
     * @test
     * @group uri
     * @group uri-javynow
     **/
    public function JavyNowのurlを整形する場合 ()
    {
        $url = 'http://javynow.com/player.php?id=MzYzMTYz&n=1&s=1&h=700';
        $url = $this->manager->resolve($url);
        $this->assertEquals('http://javynow.com/video.php?id=MzYzMTYz&n=1&s=1&h=700', $url);
    }


    /**
     * @test
     * @group uri
     * @group uri-unrelated-url
     */
    public function AdultMidnightに関係のないurlの場合 ()
    {
        $url = 'https://google.com';
        $url = $this->manager->resolve($url);

        $this->assertEquals('https://google.com', $url);
    }
}

