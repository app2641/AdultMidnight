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
}

