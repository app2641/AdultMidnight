<?php


namespace Midnight\Crawler;

use Symfony\Component\Yaml\Yaml as YamlParser;
use Zend\Config\Reader\Yaml;

use Garnet\Container,
    Midnight\Factory\CrawlerPluginFactory;

class PluginManager
{

    /**
     * plugins.ymlへのパス
     *
     * @var string
     **/
    private $plugins_yml_path = 'data/crawler/plugins.yml';


    /**
     * Plugins区画のyamlデータ
     *
     * @var array
     **/
    private $plugins;



    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct ()
    {
        $reader = new Yaml();
        $reader->setYamlDecoder(array(new YamlParser, 'parse'));
        $yaml_data = $reader->fromFile(ROOT.'/'.$this->plugins_yml_path);
        $this->plugins = $yaml_data['Plugins'];
    }


    /**
     * 指定名のプラグインを有効化されているかを判別する
     *
     * @param  string $name
     * @return boolean
     **/
    public function ifEnablePlugin ($name)
    {
        if (! isset($this->plugins[$name])) {
            throw new \Exception($name.' プラグインは存在しません');
        }

        $plugin = $this->plugins[$name];
        return ($plugin['enable']) ? true: false;
    }


    /**
     * 現在有効化されているプラグイン名を配列で返す
     *
     * @return array
     **/
    public function getEnablePluginNames ()
    {
        $plugin_names = array();

        foreach ($this->plugins as $p_name => $value) {
            if ($value['enable'] === true) {
                $plugin_names[] = $p_name;
            }
        }

        return $plugin_names;
    }


    /**
     * 指定名のプラグインを取得する
     *
     * @param  string $name
     * @return Midngiht\Crawler\PluginInterface
     **/
    public function getPlugin ($name)
    {
        $result = $this->ifEnablePlugin($name);
        if (! $result) {
            throw new \Exception('有効化されたプラグインではありません');
        }

        $container = new Container(new CrawlerPluginFactory);
        return $container->get($name);
    }
}
