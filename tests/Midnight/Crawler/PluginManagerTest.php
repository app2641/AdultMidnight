<?php


use Midnight\Crawler\PluginManager;

class PluginManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var PluginManager
     **/
    private $manager;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->manager = new PluginManager();
    }


    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    Foo プラグインは存在しません
     * @group p_manager
     * @group p_manager-is-not-exists
     */
    public function 指定プラグインが存在しない場合 ()
    {
        $p_name = 'Foo';
        $this->manager->ifEnablePlugin($p_name);
    }


    /**
     * @test
     * @group p_manager
     * @group p_manager-disable-plugin
     */
    public function 無効化されているプラグインの場合 ()
    {
        $p_name = 'TestDisablePlugin';
        $result = $this->manager->ifEnablePlugin($p_name);
        $this->assertFalse($result);
    }


    /**
     * @test
     * @group p_manager
     * @group p_manager-enable-plugin
     */
    public function 有効化されているプラグインの場合 ()
    {
        $p_name = 'AdultGeek';
        $result = $this->manager->ifEnablePlugin($p_name);
        $this->assertTrue($result);
    }


    /**
     * @test
     * @group p_manager
     * @group p_manager-enable-plugins
     */
    public function 有効化されているプラグイン名をすべて配列で取得する ()
    {
        $plugins = $this->manager->getEnablePluginNames();
        $this->assertTrue(is_array($plugins));
    }


    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    有効化されたプラグインではありません
     * @group p_manager
     * @group p_manager-get-disable-plugin
     */
    public function 無効化されているプラグインを取得しようとした場合 ()
    {
        $p_name = 'TestDisablePlugin';
        $this->manager->getPlugin($p_name);
    }


    /**
     * @test
     * @group p_manager
     * @group p_manager-get-plugin
     */
    public function 指定名のプラグインを取得する ()
    {
        $p_name = 'AdultGeek';
        $plugin = $this->manager->getPlugin($p_name);
        $this->assertInstanceOf('Midnight\Crawler\Plugin\AdultGeek', $plugin);
    }
}
