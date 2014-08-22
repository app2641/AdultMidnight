<?php


use Garnet\Container,
    Midnight\Factory\CrawlerPluginTestDataFactory;

class CrawlerPluginTestDataFactoryTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @var container
     **/
    private $container;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->container = new Container(new CrawlerPluginTestDataFactory);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-adult
     */
    public function AdultAdultプラグイン用テストデータ取得 ()
    {
        $test_data = $this->container->get('AdultAdult');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\AdultAdultTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-adult-geek
     */
    public function AdultGeekプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('AdultGeek');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\AdultGeekTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-av-selection
     */
    public function AvSelectionプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('AvSelection');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\AvSelectionTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-baaaaaa
     */
    public function Baaaaaaプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Baaaaaa');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\BaaaaaaTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-bikyaku
     */
    public function Bikyakuプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Bikyaku');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\BikyakuTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-doesu
     */
    public function Doesuプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Doesu');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\DoesuTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-download
     */
    public function Downloadプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Download');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\DownloadTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-epusta
     */
    public function Epustaプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Epusta');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\EpustaTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-matome
     */
    public function Matomeプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Matome');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\MatomeTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-minna
     */
    public function Minnaプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Minna');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\MinnaTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-muryo
     */
    public function Muryoプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Muryo');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\MuryoTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-rakuen
     */
    public function Rakuenプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Rakuen');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\RakuenTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-shikosen
     */
    public function Shikosenプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Shikosen');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\ShikosenTestData', $test_data);
    }


    /**
     * @test
     * @group test_factory
     * @group test_factory-get-youskbe
     */
    public function Youskbeプラグイン用テストデータの取得 ()
    {
        $test_data = $this->container->get('Youskbe');
        $this->assertInstanceOf('Midnight\Crawler\Plugin\TestData\YouskbeTestData', $test_data);
    }
}

