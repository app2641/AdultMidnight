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

        $this->assertEquals('http://jp.xvideos.com/video7953646', $url);
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
     * @group uri-unrelated-url
     */
    public function AdultMidnightに関係のないurlの場合 ()
    {
        $url = 'https://google.com';
        $url = $this->manager->resolve($url);

        $this->assertEquals('https://google.com', $url);
    }
}

