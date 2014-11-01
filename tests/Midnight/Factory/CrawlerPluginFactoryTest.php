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
     * @group p_factory-get-bikyaku
     */
    public function Bikyakuプラグインの取得 ()
    {
        $plugin = $this->container->get('Bikyaku');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Bikyaku', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-doesu
     */
    public function Doesuプラグインの取得 ()
    {
        $plugin = $this->container->get('Doesu');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Doesu', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-download
     */
    public function Downloadプラグインの取得 ()
    {
        $plugin = $this->container->get('Download');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Download', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-epusta
     */
    public function Epustaプラグインの取得 ()
    {
        $plugin = $this->container->get('Epusta');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Epusta', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-eroero
     */
    public function EroEroプラグインの取得 ()
    {
        $plugin = $this->container->get('EroEro');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\EroEro', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-hentai-anime
     */
    public function HentaiAnimeプラグインの取得 ()
    {
        $plugin = $this->container->get('HentaiAnime');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\HentaiAnime', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-matome
     */
    public function Matomeプラグインの取得 ()
    {
        $plugin = $this->container->get('Matome');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Matome', $plugin);
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
     * @group p_factory-get-muryo
     **/
    public function Muryoプラグインの取得 ()
    {
        $plugin = $this->container->get('Muryo');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Muryo', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-rakuen
     */
    public function Rakuenプラグインの取得 ()
    {
        $plugin = $this->container->get('Rakuen');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Rakuen', $plugin);
    }


    /**
     * @test
     * @group p_factory
     * @group p_factory-get-shikosen
     */
    public function Shikosenプラグインの取得 ()
    {
        $plugin = $this->container->get('Shikosen');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\Shikosen', $plugin);
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

