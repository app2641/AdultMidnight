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
     **/
    public function プラグイン生成テスト ()
    {
        $manager = new Midnight\Crawler\PluginManager();
        $names   = $manager->getEnablePluginNames();

        foreach ($names as $name) {
            $plugin = $this->container->get($name);

            $instance_name = 'Midnight\Crawler\Plugin\TestData\\'.$name.'TestData';
            $this->assertInstanceOf($instance_name, $plugin);
        }
    }
}

