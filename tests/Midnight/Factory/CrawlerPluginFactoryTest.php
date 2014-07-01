<?php


use Garnet\Container,
    Midnight\Factory\CrawlerPluginFactory;

class CrawlerPluginFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var container
     **/
    private $container;


    public function setUp ()
    {
        $this->container = new Container(new CrawlerPluginFactory);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-adultadult
     */
    public function AdultAdultプラグインの取得 ()
    {
        $plugin = $this->container->get('AdultAdult');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\AdultAdult', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-adultgeek
     */
    public function AdultGeekプラグインの取得 ()
    {
        $plugin = $this->container->get('AdultGeek');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\AdultGeek', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-avselection
     */
    public function AvSelectionプラグインの取得 ()
    {
        $plugin = $this->container->get('AvSelection');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\AvSelection', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-baaaaaa
     **/
    public function Baaaaaaプラグインの取得 ()
    {
        $plugin = $this->container->get('Baaaaaa');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Baaaaaa', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-minna
     */
    public function Minnaプラグインの取得 ()
    {
        $plugin = $this->container->get('Minna');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Minna', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-youskbe
     */
    public function Youskbeプラグインの取得 ()
    {
        $plugin = $this->container->get('Youskbe');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Youskbe', $plugin);
    }
}
