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
     **/
    public function プラグイン生成のテスト ()
    {
        $manager = new Midnight\Crawler\PluginManager();
        $names   = $manager->getEnablePluginNames();

        foreach ($names as $name) {
            $plugin = $this->container->get($name);

            $instance_name = 'Midnight\Crawler\Plugin\\'.$name;
            $this->assertInstanceOf($instance_name, $plugin);
        }
    }
}

